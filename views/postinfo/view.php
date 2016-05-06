<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Postinfo */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Postinfos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="postinfo-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'pacname',
            'version',
            'version_in',
            'title',
            'zip_source',
            'zip_name',
            'themepic',
            'theme_url:url',
            'status',
        ],
    ]) ?>

</div>
