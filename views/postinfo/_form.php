<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Postinfo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="postinfo-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

    <?= $form->field($model, 'pacname')->textInput(['maxlength' => true])->hint('default value is zip file name') ?>

    <?= $form->field($model, 'version')->textInput(['maxlength' => true])->hint('must enter') ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true])->hint('default value is zip file name') ?>

    <?= $form->field($model, 'zip_source_file')->fileInput() ?>

    <?= $form->field($model, 'zip_name')->textInput(['maxlength' => true])->hint('default value is zip file name') ?>

    <?= $form->field($model, 'themepic_file')->fileInput() ?>

    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
