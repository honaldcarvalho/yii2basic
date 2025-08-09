<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model croacworks\yii2basic\models\Rule */

$this->title = Yii::t('app', 'Rule').': ' .$model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Rules'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$model->actions = str_replace(';', ' | ', $model->actions);
$model->origin = str_replace(';', ' | ', $model->origin);
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <p>
                        <?= croacworks\yii2basic\widgets\DefaultButtons::widget(['controller' => 'Rule','model'=>$model,'verGroup'=>false]) ?>
                    </p>
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            'user.fullname:text:'.Yii::t('app', 'User'),
                            'group.name:text:'.Yii::t('app', 'Rule'),
                            'controller',
                            'path',
                            'actions',
                            'origin',
                            'status:boolean',
                        ],
                    ]) ?>
                </div>
                <!--.col-md-12-->
            </div>
            <!--.row-->
        </div>
        <!--.card-body-->
    </div>
    <!--.card-->
</div>
