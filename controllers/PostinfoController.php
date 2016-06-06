<?php

namespace app\controllers;

use Yii;
use app\models\Postinfo;
use app\models\PostinfoSearch;
use yii\base\Exception;
use yii\db\mssql\PDO;
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
        if (Yii::$app->user->isGuest) {
            // return $this->redirect('login');
            return $this->actionLogin();
        }

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
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function exist($pacname){
        $tmp= '\'';
        $tmp = $tmp.$pacname;
        $tmp =  $tmp. '\'';

        $row = Yii::$app->db->createCommand('SELECT * FROM `postinfo` WHERE `pacname`= '.$tmp)
            ->queryOne();
        return $row;
    }

    /**
     * Creates a new Postinfo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        if (Yii::$app->user->isGuest) {
            // return $this->redirect('login');
            return $this->actionLogin();
        }

        $model = new Postinfo();

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            $model->zip_source_file = UploadedFile::getInstances($model, 'zip_source_file');
            $model->themepic_file = UploadedFile::getInstances($model, 'themepic_file');

            foreach ($model->zip_source_file as $file){
                if($this->exist($file->baseName)){
                    return $this->render('error');
                }
            }



            $model->upload();
            $model->status = 1; //表示可用
            $sql = $model->attributes;

            unset($sql['zip_source_file']);
            unset($sql['themepic_file']);
            unset($sql['version_in']);
            $sql['version_in'] = time();
            $sql['order_id'] = 65535;

            // var_dump($sql);

            if($this->exist($model->pacname)){
                return $this->render('error');
            }

            Yii::$app->db->createCommand()->insert('postinfo',$sql)->execute();

            $this->refreshRedis();

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
        if (Yii::$app->user->isGuest) {
            // return $this->redirect('login');
            return $this->actionLogin();
        }

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

            $this->actionRefresh();

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
        if (Yii::$app->user->isGuest) {
            // return $this->redirect('login');
            return $this->actionLogin();
        }

        $model = $this->findModel($id);
        // $this->findModel($id)->delete();
        $model->status = 2; // 2表示删除
        Yii::$app->db->createCommand()->update('postinfo', $model->attributes, 'id ='.$model->id)->execute();

        $this->actionRefresh();

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

    public function actionLogin()
    {
        /*        var_dump( Yii::$app->request->getUrl());
                var_dump(Yii::$app->user->getReturnUrl());*/

        if (!\Yii::$app->user->isGuest) {
            // return $this->redirect(Yii::$app->request->getUrl());
            return $this->goBack();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
            // return $this->redirect(Yii::$app->request->getUrl());
            //var_dump(Yii::$app->user->getReturnUrl());
            //return $this->redirect(Yii::$app->request->getUrl());
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }


    public function refreshRedis(){

        $ch = curl_init();
        $timeout = 5;
        curl_setopt ($ch, CURLOPT_URL, Yii::$app->params['refresh_redis_url']);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $file_contents = curl_exec($ch);
        curl_close($ch);
        echo $file_contents;
    }

    public function actionOperator()
    {
        if (Yii::$app->user->isGuest) {
            // return $this->redirect('login');
            return $this->actionLogin();
        }


        if (Yii::$app->request->isPost ) {
            $operator = Yii::$app->request->post("operator");


            $operator_tmp = $this->processText($operator);

            $operators = explode("\n",$operator_tmp);

            $sql['order_id'] =  65535;
            Yii::$app->db->createCommand()->update('postinfo',$sql)->execute();

            foreach ($operators as $index => $item) {
                $sql['order_id'] = $index + 1;
                $item = $this->removeNewLine($item);

                $new_sql = "UPDATE `postinfo` SET `order_id`=".$index."  WHERE  `postinfo`.`pacname`= ".$this->str_wrap($item);

                Yii::$app->db->createCommand($new_sql)->execute();


/*                $db = new PDO("mysql:host=localhost;dbname=yii2basic", 'root', 'rootroot');
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                try {
                    var_dump($db->exec($new_sql));
                }
                catch (PDOException $e)
                {
                    echo $e->getMessage();
                    die();
                }*/


            }

            $this->actionRefresh();
             return $this->goHome();
            /* var_dump(Yii::$app->request->post("operator"));

             return "hello,operator";*/
        } else {
            return $this->render('operator');
        }
    }

    public function actionRefresh(){

        /*        $ch = curl_init();
                $timeout = 5;
                curl_setopt ($ch, CURLOPT_URL, Yii::$app->params['refresh_redis_url']);
                curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
                $file_contents = curl_exec($ch);
                curl_close($ch);
                return $file_contents;*/

        Yii::$app->cache->flush();

        $themes = Yii::$app->db->createCommand('SELECT `order_id` as `id`, `pacname` as `packageName` ,`version`,`title`,`zip_source` as `downloadUrl`,`theme_url` as `previewImageUrl`  FROM `postinfo` where `status`=1 AND `order_id`<>65535 ORDER BY `id` ASC' )->queryAll();
        $themes_new = array();
        if($themes != null){
            foreach($themes as $theme){
                $id = $theme['id'];
                // unset($theme['id']);
                Yii::$app->cache->set("theme".$id,json_encode($theme));

                $themes_new[] = $theme;
            }
            // unset($themes['id']);
            Yii::$app->cache->set("themes",json_encode($themes_new));
        }

        echo "reset redis data ok";
    }

    public function processText($text) {
        $text = strip_tags($text);
        $text = trim($text);
        $text = htmlspecialchars($text);
        return $text;
    }


    public function str_wrap($string = '', $char = '"')
    {
        return str_pad($string, strlen($string) + 2, $char, STR_PAD_BOTH);
    }

    public function removeNewLine($str)
    {
        return str_replace(array("\r", "\n", "\r\n", "\n\r"), '', $str);
    }
}
