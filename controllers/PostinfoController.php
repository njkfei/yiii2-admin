<?php

namespace app\controllers;

use Yii;
use app\models\Postinfo;
use app\models\PostinfoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\filters\auth\HttpBasicAuth;
use app\models\User;


/**
 * PostinfoController implements the CRUD actions for Postinfo model.
 */
class PostinfoController extends Controller
{
    /**
     * Lists all Postinfo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PostinfoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Postinfo model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {


/*        $state = [
            '0' => '未处理',
            '1' => 'OK',
            '2' => '已删除',
        ];
        var_dump( $state["0"]);*/



        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Postinfo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Postinfo();

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            $model->zip_source_file = UploadedFile::getInstances($model, 'zip_source_file');
            $model->themepic_file = UploadedFile::getInstances($model, 'themepic_file');

            if ($model->upload()) {
                // file is uploaded successfully
                echo "upload controller ok";
            }else{
                echo "upload controller fail";
            }

           $model->status = 1; //表示可用
           $sql = $model->attributes;

            unset($sql['zip_source_file']);
            unset($sql['themepic_file']);
            unset($sql['version_in']);
            $sql['version_in'] = time();

           // var_dump($sql);

            Yii::$app->db->createCommand()->insert('postinfo',$model->attributes)->execute();

            $searchModel = new PostinfoSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Postinfo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        // var_dump($model);

        if (Yii::$app->request->isPost  && $model->load(Yii::$app->request->post())) {
            $model->zip_source_file = UploadedFile::getInstances($model, 'zip_source_file');
            $model->themepic_file = UploadedFile::getInstances($model, 'themepic_file');
            if ($model->upload()) {
            }

            $sql = $model->attributes;

            unset($sql['zip_source_file']);
            unset($sql['themepic_file']);
            unset($sql['version_in']);
            $sql['version_in'] = time();

            var_dump($sql);

            Yii::$app->db->createCommand()->update('postinfo',$sql, 'id ='.$model->id)->execute();

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Postinfo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
       // $this->findModel($id)->delete();
        $model->status = 2; // 2表示删除
        Yii::$app->db->createCommand()->update('postinfo', $model->attributes, 'id ='.$model->id)->execute();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Postinfo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Postinfo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Postinfo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
