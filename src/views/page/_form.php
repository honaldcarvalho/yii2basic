<?php

use croacworks\yii2basic\models\Language;
use croacworks\yii2basic\models\Section;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use croacworks\yii2basic\controllers\AuthorizationController;
use croacworks\yii2basic\controllers\ControllerCommon;
use croacworks\yii2basic\widgets\TinyMCE;

/** @var yii\web\View $this */
/** @var croacworks\yii2basic\models\Page $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="page-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'group_id')->dropDownList(yii\helpers\ArrayHelper::map(croacworks\yii2basic\models\Group::find()
    ->where(['in','id', AuthorizationController::userGroups()])
    ->asArray()->all(), 'id', 'name'), ['class'=>'form-control'])->label(Yii::t('app','Group')) ?>

    <?= $form->field($model, 'section_id')->dropDownList(
        yii\helpers\ArrayHelper::map(Section::find()->where(['in','group_id',AuthorizationController::userGroups()])->all(), 'id', 'name'), 
        ['prompt' => '-- selecione uma secção --']) ?>

    <?= $form->field($model, 'language_id')->dropDownList(
        yii\helpers\ArrayHelper::map(Language::find()->all(), 'id', 'name'), 
        ['prompt' => '-- selecione uma lingua --']) ?>

    <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'content')->widget(\croacworks\yii2basic\widgets\TinyMCE::class, [
        'options' => ['rows' => 20]
    ]); ?>

    <?= $form->field($model, 'custom_css')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'custom_js')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'keywords')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'status')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('<i class="fas fa-save mr-2"></i>'.Yii::t('croacworks\yii2basic','Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
