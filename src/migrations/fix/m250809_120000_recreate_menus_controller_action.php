<?php

use yii\db\Migration;
use yii\helpers\Inflector;

/**
 * Recreates all menu items in the new format, populating `controller` (FQCN)
 * and `action` from `visible` + `path` when necessary.
 *
 * - Preserves IDs and `menu_id` (hierarchy).
 * - Keeps label, icons, url, active, order, only_admin, status.
 * - Converts `visible` "controller-id;action" to FQCN + action.
 * - If no action in `visible`, uses '*'.
 * - For groups/headers (without route), leaves `controller`/`action` null.
 */
class m250809_120000_recreate_menus_controller_action extends Migration
{
    public function safeUp()
    {
        $this->execute('SET foreign_key_checks = 0;');

        // Load all existing menus
        $menus = (new \yii\db\Query())
            ->from('menus')
            ->orderBy(['menu_id' => SORT_ASC, 'order' => SORT_ASC, 'id' => SORT_ASC])
            ->all();

        $normalized = [];

        foreach ($menus as $menu) {
            $fqcn   = $menu['controller'] ?? null;
            $action = $menu['action'] ?? null;

            // If controller/action are not yet set, try to convert from visible + path
            if (empty($fqcn)) {
                $visible_original = $menu['visible'] ?? ''; // Preserve the original visible value
                $path    = $menu['path'] ?? 'app';

                if ($visible_original) {
                    if (strpos($visible_original, ';') !== false) {
                        [$controllerId, $actionFromVisible] = explode(';', $visible_original, 2);
                        $actionFromVisible = trim($actionFromVisible) !== '' ? trim($actionFromVisible) : '*';
                    } else {
                        $controllerId      = $visible_original;
                        $actionFromVisible = '*';
                    }

                    $controllerBase = Inflector::id2camel($controllerId, '-') . 'Controller';

                    switch ($path) {
                        case 'app':
                            $fqcn = "app\controllers{$controllerBase}";
                            break;
                        case 'app/custom':
                            $fqcn = "app\controllers\custom{$controllerBase}";
                            break;
                        case 'croacworks/controllers':
                            $fqcn = "croacworks\yii2basic\controllers{$controllerBase}";
                            break;
                        default:
                            // Ex.: 'vendor/pacote/controllers' -> 'vendor\pacote\controllers\ControllerName'
                            $fqcn = str_replace('/', '\'', $path) . "{$controllerBase}";
                            break;
                    }

                    $action = $action ?? $actionFromVisible;
                }
            }

            // For headers or items without a route, keep null
            if (isset($fqcn) && $fqcn !== '' && empty($action)) {
                $action = '*';
            }

            $normalized[] = [
                'id'         => (int)$menu['id'],
                'menu_id'    => $menu['menu_id'] !== null ? (int)$menu['menu_id'] : null,
                'label'      => $menu['label'] ?? '',
                'icon_style' => $menu['icon_style'] ?? 'fas',
                'icon'       => $menu['icon'] ?? null,
                'controller' => $fqcn ?: null,
                'action'     => $action ?: null,
                'url'        => $menu['url'] ?? '#',
                'active'     => $menu['active'] ?? '',
                'order'      => isset($menu['order']) ? (int)$menu['order'] : 0,
                'only_admin' => isset($menu['only_admin']) ? (int)$menu['only_admin'] : 0,
                'status'     => isset($menu['status']) ? (int)$menu['status'] : 1,
                'visible'    => $menu['visible'] ?? null, // <-- Add this line
            ];
        }

        // Clear and reinsert normalized data
        $this->delete('menus');

        // Reinsert preserving IDs and hierarchy
        foreach ($normalized as $row) {
            Yii::$app->db->createCommand()->insert('menus', $row)->execute();
        }

        $this->execute('SET foreign_key_checks = 1;');
    }

    public function safeDown()
    {
        echo "This migration rewrites the content of `menus` and cannot be automatically reverted.\n";
        return false;
    }
}