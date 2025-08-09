<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model croacworks\yii2basic\models\LicenseType */

$this->title = Yii::t('app', 'Create License Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'License Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <?=$this->render('_form', [
                        'model' => $model
                    ]) ?>
                </div>
            </div>
        </div>
        <!--.card-body-->
    </div>
    <!--.card-->
</div>