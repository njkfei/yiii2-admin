<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PostinfoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Export postinfos list';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="postinfo-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <div class="alert alert-success" role="alert">
        <?php
        foreach($lists as $item){
            echo "<p>$item</p>";
        }
        ?>
    </div>


<!--    <div class='btn btn-warning'>-->
<!--        <a href="tmp.txt" class="btn btn-lg btn-outline">Save As</a>-->
<!--    </div>-->

</div>
