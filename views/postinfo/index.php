<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PostinfoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Postinfos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="postinfo-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Postinfo', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Create Operator', ['operator'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Export Operator', ['export'], ['class' => 'btn btn-warning']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'pacname',
            'version',
            'title',
            [
                'label' => 'modify_time',
                'value' => function($model){
                    return   date("Y M y h:i:s",  $model->version_in);
                }
            ],
            /*            'zip_source:url',*/
            'zip_name',
            /*            'themepic',
                        'theme_url:url',*/
            [
                'label'=>'pic_preview',
                'format'=>'raw',
                'value'=>function($model){
                    return Html::img($model->theme_url,['width' => 50]);
                }
            ],
            [
                'label'=>'status',
                'attribute' => 'status',
                'value' => function ($model) {
                    $state = [
                        '0' => '未处理',
                        '1' => 'OK',
                        '2' => '已删除',
                    ];
                    return $state[$model->status];
                },
                'headerOptions' => ['width' => '120']
            ],
            'order_id',
            [
                'header'=> 'operations',
                'class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
