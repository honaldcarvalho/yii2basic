<?php

namespace croacworks\yii2basic\themes\adminlte3\widgets;

trait WidgetTrait
{
    public $loadingStyle = false;

    protected function renderLoadingStyle($config = [])
    {
        $config = array_merge([
            'id' => $this->options['id'] . '-overlay'
        ], $config);
        return $this->loadingStyle ? LoadingStyle::widget($config) : '';
    }
}