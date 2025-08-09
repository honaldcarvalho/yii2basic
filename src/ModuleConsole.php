<?php

namespace croacworks\yii2basic;

use yii\base\Module;

/**
 * Módulo para uso exclusivo no console, evitando conflitos com controllers web/API.
 */
class ModuleConsole extends Module
{
    /**
     * Namespace padrão para comandos deste módulo no console
     * (ex: php yii basics/hello)
     */
    public $controllerNamespace = 'croacworks\yii2basic\commands';

    public function init()
    {
        parent::init();
        // Qualquer inicialização específica para console pode ir aqui
    }
}
