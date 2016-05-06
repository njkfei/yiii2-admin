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
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

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

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
