<?php

use croacworks\yii2basic\controllers\AuthorizationController;
use croacworks\yii2basic\models\Group;;
use croacworks\yii2basic\models\Language;
use croacworks\yii2basic\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var croacworks\yii2basic\models\UserUpdate $model */
/** @var yii\widgets\ActiveForm $form */

$query = Group::find();
if(isset($group)) {
    $query->where(['id'=>$group->id]);
}
$groups = $query->andWhere(['<>','name','*'])
->asArray()->all();

$script = <<< JS
    $('#user-group_id').select2({tags:true,placeholder:'-- Selecione um grupo -- ', width:'100%',
        createTag: function (params) {
            var term = $.trim(params.term);
            if (term === '') {
                return null;
            }
            return {
                id: term,
                text: term,
                newTag: true // add additional parameters
            }
        }
    });
JS;

$this->registerJs($script);

?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'file_id')->fileInput() ?>
            <?= \croacworks\yii2basic\widgets\UploadFoto::widget([
                'imagem'=> $model?->file?->url,
                'fileField'=>'user-file_id',
                'aspectRatio'=>'1/1',
                'maxWidth'=>'1920'
            ])?>

        </div>
    </div>

    <div class="row">

        <div class="col-md-12">
            
            <?=  AuthorizationController::isAdmin() ? $form->field($model, 'group_id')->dropDownList(yii\helpers\ArrayHelper::map(
            $groups, 'id', 'name'), ['prompt' => '-- Selecione um Grupo --']) : ''?>
            
            <?= $form->field($model, 'fullname')->textInput() ?>
            
            <?= $form->field($model, 'theme')->dropDownList(['light'=>'Light','dark'=>'Dark']) ?>

            <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::class, [
                'mask' => '(99) 9 9999-9999',
            ]) ?>
            <?= $form->field($model, 'email')->textInput() ?>
            <?= $form->field($model, 'language_id')->dropDownList(yii\helpers\ArrayHelper::map(
                Language::find()
                    ->select('id,name')->asArray()->all(),
                'id',
                'name'
            )) ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <?= $form->field($model, 'password_confirm')->passwordInput() ?>

            <?=  AuthorizationController::isAdmin() ? $form->field($model, 'status')->dropDownList([9 => Yii::t('app', 'Inactive'), 10 => Yii::t('app', 'Active'), 20 => Yii::t('app', 'No system user')]) : '' ?>

            <div class="form-group">
                <?= Html::submitButton('<i class="fas fa-save mr-2"></i>' . Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>