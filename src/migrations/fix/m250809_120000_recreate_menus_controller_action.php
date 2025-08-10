<?php

use yii\db\Migration;
use yii\helpers\Inflector;

class m250809_120000_recreate_menus_controller_action extends Migration
{
    public function safeUp()
    {
        $this->execute('SET foreign_key_checks = 0;');

        $menus = (new \yii\db\Query())
            ->from('menus')
            ->orderBy(['menu_id' => SORT_ASC, 'order' => SORT_ASC, 'id' => SORT_ASC])
            ->all();

        $normalized = [];

        foreach ($menus as $menu) {
            $fqcn   = $menu['controller'] ?? null;
            $action = $menu['action'] ?? null;

            // 1) Se já existe FQCN, normaliza vendor -> croacworks\yii2basic
            if (!empty($fqcn)) {
                $fqcn = $this->normalizeVendorNamespace($fqcn);
            } else {
                // 2) Caso contrário, deriva de visible + path
                $visible = $menu['visible'] ?? '';
                $path    = $menu['path'] ?? 'app';

                if ($visible) {
                    if (strpos($visible, ';') !== false) {
                        [$controllerId, $actionFromVisible] = explode(';', $visible, 2);
                        $actionFromVisible = trim($actionFromVisible) !== '' ? trim($actionFromVisible) : '*';
                    } else {
                        $controllerId      = $visible;
                        $actionFromVisible = '*';
                    }

                    $controllerBase = Inflector::id2camel($controllerId, '-') . 'Controller';
                    $baseNs = $this->resolveNamespaceFromPath($path); // já migra weebz -> croacworks

                    $fqcn   = $baseNs . '\\' . $controllerBase;
                    $action = $action ?? $actionFromVisible;
                }
            }

            // Se tem FQCN e não tem action, assume '*'
            if (!empty($fqcn) && empty($action)) {
                $action = '*';
            }

            // Regrava `visible` se estiver vazio
            $visibleOriginal = isset($menu['visible']) ? trim((string)$menu['visible']) : '';
            $visibleValue    = $visibleOriginal;

            $computedControllerId = null;
            if (!empty($fqcn)) {
                $class = preg_replace('~^.*\\\\~', '', $fqcn);      // ClientController
                $base  = preg_replace('/Controller$/', '', $class); // Client
                $computedControllerId = Inflector::camel2id($base); // client
            } elseif (!empty($visibleOriginal)) {
                [$ctrlId] = array_pad(explode(';', $visibleOriginal, 2), 2, null);
                $computedControllerId = $ctrlId ?: null;
            }

            if ($visibleValue === '' && $computedControllerId) {
                $visibleValue = $computedControllerId . ';' . ($action ?: '*');
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

                // legados preservados/normalizados (opcional)
                'visible'    => $visibleValue !== '' ? $visibleValue : null,
                'path'       => $menu['path'] ?? null,
            ];
        }

        $this->delete('menus');

        foreach ($normalized as $row) {
            Yii::$app->db->createCommand()->insert('menus', $row)->execute();
        }

        $this->execute('SET foreign_key_checks = 1;');
    }

    public function safeDown()
    {
        echo "Esta migration reescreve o conteúdo de `menus` e não pode ser revertida automaticamente.\n";
        return false;
    }

    /**
     * Converte qualquer namespace antigo do pacote para croacworks\yii2basic\controllers.
     */
    private function normalizeVendorNamespace(string $fqcn): string
    {
        // weebz\yii2basics\controllers\X -> croacworks\yii2basic\controllers\X
        $fqcn = preg_replace(
            '~^weebz\\\\yii2basics\\\\controllers\\\\~',
            'croacworks\\yii2basic\\controllers\\',
            $fqcn
        );

        // casos residuais: weebz\controllers\X -> croacworks\yii2basic\controllers\X
        $fqcn = preg_replace(
            '~^weebz\\\\controllers\\\\~',
            'croacworks\\yii2basic\\controllers\\',
            $fqcn
        );

        return $fqcn;
    }

    /**
     * Mapeia `path` antigo para o namespace base correto.
     * - "app"              -> "app\controllers"
     * - "app/custom"       -> "app\controllers\custom"
     * - Qualquer caminho com "weebz" -> "croacworks\yii2basic\controllers"
     * - Qualquer caminho com "croacworks" -> "croacworks\yii2basic\controllers"
     * - Fallback: se terminar com "/controllers", usa esse trecho, mas migrando vendor para croacworks\yii2basic.
     */
    private function resolveNamespaceFromPath(string $path): string
    {
        $p = trim($path, " \t\n\r\0\x0B/\\");
        $p = str_replace('\\', '/', $p);

        if ($p === 'app') {
            return 'app\\controllers';
        }
        if ($p === 'app/custom') {
            return 'app\\controllers\\custom';
        }

        // Qualquer coisa apontando para o pacote antigo cai no novo
        if (strpos($p, 'weebz') !== false) {
            return 'croacworks\\yii2basic\\controllers';
        }

        // Já no novo vendor
        if (strpos($p, 'croacworks') !== false) {
            return 'croacworks\\yii2basic\\controllers';
        }

        // Fallback: tenta usar o final "*/controllers"
        if (preg_match('~/(controllers)(/.*)?$~', $p)) {
            return 'croacworks\\yii2basic\\controllers';
        }

        // Último recurso: assume pacote novo
        return 'croacworks\\yii2basic\\controllers';
    }
}
