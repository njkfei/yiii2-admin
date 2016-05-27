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


    <div class="postinfo-form">

        <form role="form" action="operator" method="post">
            <div class="form-group">
                <label for="name">运营信息列表</label>
                <textarea  class="form-control" id="operator" name="operator"
                       placeholder="请输入运营信息列表" rows="30" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary" acton="operator" method="post">提交</button>
            <!-- _csrf -->
            <input type="hidden"
                   name="<?= \Yii::$app->request->csrfParam; ?>"
                   value="<?= \Yii::$app->request->getCsrfToken();?>">
        </form>
        </div>

</div>
