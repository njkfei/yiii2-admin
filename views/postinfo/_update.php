<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Postinfo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="postinfo-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

    <?= $form->field($model, 'pacname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'version')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zip_source')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zip_source_file')->fileInput() ?>

    <?= $form->field($model, 'zip_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'themepic')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'themepic_file')->fileInput() ?>
    <?= $form->field($model, 'theme_url')->textInput(['maxlength' => true]) ?>
    <?=  $form->field($model, 'status')->dropDownList(['1'=>'ok','2'=>'delete'], ['prompt'=>'请选择','style'=>'width:120px']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
