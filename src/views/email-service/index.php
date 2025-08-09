<?php

use croacworks\yii2basic\widgets\DefaultButtons;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel croacworks\yii2basic\models\EmailServiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Email Services');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                     <div class="col-md-12">
                            <?= croacworks\yii2basic\widgets\DefaultButtons::widget(
                            [
                                'controller' => Yii::$app->controller->id,
                                'show'=>['create'],'buttons_name'=>['create'=>Yii::t("backend","Create Email Sevice")]
                            ]) ?>
                        </div>
                    </div>

                    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            'description',
                            'scheme',
                            'enable_encryption:boolean',
                            'encryption',
                            'host',
                            //'username',
                            //'password',
                            //'port',

                            ['class' =>'croacworks\yii2basic\components\gridview\ActionColumn',],
                        ],
                        'summaryOptions' => ['class' => 'summary mb-2'],
                        'pager' => [
                            'class' => 'yii\bootstrap5\LinkPager',
                        ]
                    ]); ?>


                </div>
                <!--.card-body-->
            </div>
            <!--.card-->
        </div>
        <!--.col-md-12-->
    </div>
    <!--.row-->
</div>
