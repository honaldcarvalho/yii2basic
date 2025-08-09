<?php

/* @var $this \yii\web\View */
/* @var $content string */

use croacworks\yii2basic\controllers\ControllerCommon;
use croacworks\yii2basic\models\Configuration;
use croacworks\yii2basic\themes\adminlte3\assets\FontAwesomeAsset;
use croacworks\yii2basic\themes\adminlte3\assets\PluginAsset;
use croacworks\yii2basic\themes\adminlte3\assets\WeebzAsset;
use croacworks\yii2basic\widgets\Alert;

FontAwesomeAsset::register($this);
PluginAsset::register($this)->add(['fontawesome', 'icheck-bootstrap', 'fancybox', 'jquery-ui', 'toastr', 'select2', 'sweetalert2']);
WeebzAsset::register($this);
$this->registerCssFile('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700');
$this->registerCssFile('https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css');
PluginAsset::register($this)->add(['fontawesome', 'toastr']);
$params = Configuration::get();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $params->title; ?> | Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= Yii::getAlias('@web') ?>/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= Yii::getAlias('@web') ?>/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= Yii::getAlias('@web') ?>/favicon-16x16.png">
    <link rel="manifest" href="<?= Yii::getAlias('@web') ?>/site.webmanifest">
    <?php $this->registerCsrfMetaTags() ?>
    <?php
    $script = <<< JS
        Fancybox.bind("[data-fancybox]");
        $(document).on('click', '[data-fancybox]', function () {
            if($.fancybox === undefined || $.fancybox === null) {
                console.log('Fancybox is not defined. Please ensure the Fancybox plugin is loaded.');
                return false;
            }
            $.fancybox.showLoading = function () {
                if ($('#custom-loading').length === 0) {
                    $('body').append('<div id="custom-loading" style="position:fixed;top:0;left:0;width:100%;height:100%;z-index:9999;background:rgba(255,255,255,0.8);display:flex;align-items:center;justify-content:center;font-size:20px;">Carregando...</div>');
                }
            };

            $.fancybox.hideLoading = function () {
                $('#custom-loading').remove();
            };

            $.fancybox.showLoading();
        });

        // Esconde após abrir o fancybox
        $(document).on('afterShow.fb', function () {
            $.fancybox.hideLoading();
        });

        // Também remove ao fechar (garantia extra)
        $(document).on('afterClose.fb', function () {
            $.fancybox.hideLoading();
        });
    JS;
    $this->registerJs($script);
    ?>
    <?php $this->head() ?>
    <link href="<?= Yii::getAlias('@web') ?>/css/site.css" rel="stylesheet">
</head>

<body class="hold-transition login-page">
    <?php $this->beginBody() ?>
    <?= Alert::widget() ?>
    <div class="login-box">
        <?= $content ?>
    </div>
    <!-- /.login-box -->
    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>