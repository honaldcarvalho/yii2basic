<?php

namespace croacworks\yii2basic\gii\generators\model;

class CustomGenerator extends Generator
{
    public function getName()
    {
        return 'Model Generator (Croac)';
    }

    public function getDescription()
    {
        return 'Gera modelos estendidos de ModelCommon com suporte a verGroup, enums, Yii::t etc.';
    }

    public function requiredTemplates()
    {
        return ['model.php'];
    }

    public function generate()
    {
        // Define explicitamente o template customizado
        $this->template = '@vendor/croacworks/yii2basic/src/gii/generators/model/custom/';
        return parent::generate();
    }
}
