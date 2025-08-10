<?php

use yii\db\Migration;
use yii\helpers\Inflector;

/**
 * Recria todos os itens do menu no formato novo, preenchendo `controller` (FQCN)
 * e `action` a partir de `visible` + `path` quando necessário.
 *
 * - Preserva os IDs e os `menu_id` (hierarquia).
 * - Mantém label, ícones, url, active, order, only_admin, status.
 * - Converte `visible` "controller-id;action" para FQCN + action.
 * - Quando não houver action em `visible`, usa '*'.
 * - Para grupos/headers (sem rota), deixa `controller`/`action` nulos.
 * - Agora também regrava `visible` (ou mantém o original não-vazio).
 */
class m250809_120000_recreate_menus_controller_action extends Migration
{
    public function safeUp()
    {
        $this->execute('SET foreign_key_checks = 0;');

        // Carrega todos os menus existentes
        $menus = (new \yii\db\Query())
            ->from('menus')
            ->orderBy(['menu_id' => SORT_ASC, 'order' => SORT_ASC, 'id' => SORT_ASC])
            ->all();

        $normalized = [];

        foreach ($menus as $menu) {
            $fqcn   = $menu['controller'] ?? null;
            $action = $menu['action'] ?? null;

            // Se ainda não tem controller/action, tenta converter a partir de visible + path
            if (empty($fqcn)) {
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

                    switch ($path) {
                        case 'app':
                            $fqcn = "app\\controllers\\{$controllerBase}";
                            break;
                        case 'app/custom':
                            $fqcn = "app\\controllers\\custom\\{$controllerBase}";
                            break;
                        case 'croacworks/controllers':
                            $fqcn = "croacworks\\yii2basic\\controllers\\{$controllerBase}";
                            break;
                        default:
                            // Ex.: 'vendor/pacote/controllers' -> 'vendor\pacote\controllers\ControllerName'
                            $fqcn = str_replace('/', '\\', $path) . "\\{$controllerBase}";
                            break;
                    }

                    $action = $action ?? $actionFromVisible;
                }
            }

            // Para headers ou itens sem rota, mantém null
            if (isset($fqcn) && $fqcn !== '' && empty($action)) {
                $action = '*';
            }

            // ===== Novo: calcular/garantir o `visible` =====
            $visibleOriginal = isset($menu['visible']) ? trim((string)$menu['visible']) : '';
            $visibleValue    = $visibleOriginal;

            // Deriva controller-id a partir do FQCN (quando existir)
            $computedControllerId = null;
            if (!empty($fqcn)) {
                $class = preg_replace('~^.*\\\\~', '', $fqcn);         // ex.: ClientController
                $base  = preg_replace('/Controller$/', '', $class);    // ex.: Client
                $computedControllerId = Inflector::camel2id($base);    // ex.: client
            } elseif (!empty($visibleOriginal)) {
                // Quando veio de visible "client;index"
                [$ctrlId] = array_pad(explode(';', $visibleOriginal, 2), 2, null);
                $computedControllerId = $ctrlId ?: null;
            }

            if ($visibleValue === '' && $computedControllerId) {
                $visibleValue = $computedControllerId . ';' . ($action ?: '*');
            }
            // ===============================================

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

                // Campos legados preservados/normalizados
                'visible'    => $visibleValue !== '' ? $visibleValue : null,
                'path'       => $menu['path'] ?? null,
            ];
        }

        // Limpa e reinsere já normalizado
        $this->delete('menus');

        // Reinsere preservando IDs e hierarquia
        foreach ($normalized as $row) {
            Yii::$app->db->createCommand()->insert('menus', $row)->execute();
        }

        $this->execute('SET foreign_key_checks = 1;');
    }

    public function safeDown()
    {
        echo "Esta migration reescreve o conteúdo de `menus` e não pode ser revertida automaticamente.\n";
        return true;
    }
}
