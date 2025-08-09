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
WeebzAsset::register($this);
$this->registerCssFile('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700');
$this->registerCssFile('https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css');
PluginAsset::register($this)->add(['fontawesome','toastr']);
$configuration = Configuration::get();
$image = ControllerCommon::getAssetsDir() . '/img/croacworks-logo-hq.png';
if($configuration->file !== null){
    $image = $configuration->file->url;
}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $configuration->title; ?> | Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= Yii::getAlias('@web') ?>/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= Yii::getAlias('@web') ?>/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= Yii::getAlias('@web') ?>/favicon-16x16.png">
    <link rel="manifest" href="<?= Yii::getAlias('@web') ?>/site.webmanifest">
    <?php $this->registerCsrfMetaTags() ?>
    <?php $this->head() ?>
    <link href="<?= Yii::getAlias('@web') ?>/css/site.css" rel="stylesheet">
</head>
<body class="hold-transition login-page">
<?php  $this->beginBody() ?>
<?= Alert::widget() ?>
<div class="login-box">
    <div class="login-logo">
    <img src="<?= $image; ?>" alt="" class="w-50 brand-image elevation-3"><br>
        <b><?=$configuration->title;?></b> | LOGIN</a>
    </div>
    <!-- /.login-logo -->

    <?= $content ?>
</div>
<!-- /.login-box -->
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>