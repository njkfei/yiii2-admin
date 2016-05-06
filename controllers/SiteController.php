<?php

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\AccessControl;
use yii\rest\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use Aws;
use Aws\S3\S3Client;
use app\models\UploadForm;
use yii\web\UploadedFile;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionUpload()
    {
        $model = new UploadForm();

        if (Yii::$app->request->isPost) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->upload()) {
                // file is uploaded successfully
                return;
            }
        }

        return $this->render('upload', ['model' => $model]);
    }


    public function actionRedis(){
        Yii::$app->cache->set('test', 'hehe..');
        echo Yii::$app->cache->get('test'), "\n";

        Yii::$app->cache->set('test1', 'haha..', 5);
        echo '1 ', Yii::$app->cache->get('test1'), "\n";
        sleep(6);
        echo '2 ', Yii::$app->cache->get('test1'), "\n";
        return 'hello,world';
    }

    public function actionDb(){
        $posts = Yii::$app->db->createCommand('SELECT * FROM ysyy_user')
            ->queryAll();

        echo json_encode($posts);
    }

    public function  actionThemes(){
        $posts = Yii::$app->db->createCommand('SELECT * FROM themes')
            ->queryAll();

        echo json_encode($posts);
    }

    public function actionTt(){
        $id=1;
        $theme = Yii::$app->cache->get("theme".$id);

        if($theme==false){
            $theme = Yii::$app->db->createCommand('SELECT * FROM themes ')->queryOne();

            echo json_encode($theme);

            Yii::$app->cache->set("theme".$id,json_encode($theme));
        }

        return $theme;
    }

    public function actionAws()
    {
        $config = array(
            'region' => 'ap-northeast-1',
            'credentials' => [
                'key' => 'AKIAIXGXRS6IHTVI23QQ',
                'secret' => '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',
            ],
            'version' => 'latest',
            // 'debug'   => true
        );

        $sdk = new Aws\Sdk($config);

        $client = $sdk->createS3();

        /*     $result = $client->listBuckets();

             foreach ($result['Buckets'] as $bucket) {
                 echo $bucket['Name'] . "\n";
             }*/


        $params = [
            'Bucket' => 'theme.kkk',
            'Key' => 'tea1',
            'SourceFile' => 'C:/wamp2016_new/www/yii2/README.md',
            // 'Body'   => fopen('C:/wamp2016_new/www/yii2/README.md', 'r'),
            'ContentType' => 'text/plain',
            'ACL' => 'public-read',
            'StorageClass' => 'REDUCED_REDUNDANCY',
        ];

// Using operation methods creates command implicitly.
        // $result = $client->putObject($params);
        try {
        $result = $client->putObject(array(
            'Bucket' => 'theme.kkk',
            'Key' => 'tea1',
            'SourceFile' => 'C:/wamp2016_new/www/yii2/README.md',
            'ContentType' => 'text/plain',
      //      'ACL' => 'public-read',
        ));
            // Print the URL to the object.
            echo $result['ObjectURL'] . "\n";
        } catch (S3Exception $e) {
            echo $e->getMessage() . "\n";
        }

      //  $object = $client->getObject( array(        'Bucket' => 'theme.kkk',
      //      'Key'    => 'tea'));

       // echo $result['ObjectURL'];
echo $result;
        //echo $object;
    }

    public function actionAws2(){
        $config = array(
            'region' => 'ap-northeast-1',
            'credentials' => [
            'key' => 'AKIAIXGXRS6IHTVI23QQ',
            'secret' => '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',
        ],
            'version' => 'latest',
           // 'debug'   => true
        );

        $sdk = new Aws\Sdk($config);

        $client = $sdk->createS3();



       $object = $client->getObject( array(        'Bucket' => 'theme.kkk',
            'Key'    => 'tea1'));

        echo $object;
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
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

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
}
