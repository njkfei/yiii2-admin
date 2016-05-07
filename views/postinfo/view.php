<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Postinfo */

function statusMap($model) {
    $state = [
        '0' => '未处理',
        '1' => 'OK',
        '2' => '已删除',
    ];
    return $state[$model->status];
}

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
            'title',
            [
                'label' => 'modify_time',
                'value' => date("Y M y h:i:s",  $model->version_in),
            ],
            'zip_source:url',
            'zip_name',
            'themepic',
            'theme_url:url',
            [
                'label'=>'pic_preview',
                'format'=>'raw',
                'value'=> Html::img($model->theme_url,['width' => 200]),
            ],
            [
                'label'=>'status',
                'attribute' => 'status',
                'value' => statusMap($model),

            ],
        ],
    ]) ?>

</div>
