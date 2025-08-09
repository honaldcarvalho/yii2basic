<?php

use croacworks\yii2basic\controllers\AuthorizationController;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/* @var $this yii\web\View */
/* @var $model croacworks\yii2basic\models\Folder */
/* @var $form yii\bootstrap5\ActiveForm */
?>

<div class="folder-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'folder_id')->dropDownList(yii\helpers\ArrayHelper::map(croacworks\yii2basic\models\Folder::find()
    ->where(['in','group_id', AuthorizationController::userGroups()])
    ->asArray()->all(), 'id', 'name'), ['class'=>'form-control', 'prompt' => Yii::t('app','-- Select Folder --')]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'external')->checkbox() ?>

    <?= $form->field($model, 'status')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('<i class="fas fa-save mr-2"></i>'.Yii::t('app','Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
