<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Postinfo */

$this->title = 'Create Postinfo';
$this->params['breadcrumbs'][] = ['label' => 'Postinfos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="postinfo-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
