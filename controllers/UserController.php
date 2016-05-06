<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\QueryParamAuth;

class UserController extends Controller
{
    public $modelClass = 'app\models\User';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                QueryParamAuth::className(),
            ],
        ];
        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        // 注销系统自带的实现方法
        unset($actions['index'], $actions['update'], $actions['create'], $actions['delete'], $actions['view']);
        return $actions;
    }

    public function actionIndex()
    {
        $modelClass = $this->modelClass;
        $query = $modelClass::find();
        return $query;
    }

    public function actionFind($id)
    {
        $modelClass = $this->modelClass;
        $query = $modelClass::findIdentity($id);

        return $query;
    }

    public function actionCreate()
    {
        $model = new $this->modelClass();
        echo $model->load(Yii::$app->request->post());
/*        $model = new $this->modelClass();
        if ($model->load(Yii::$app->request->post()) && $model->save(Yii::$app->request->post())) {
            return findIdentity($model->$id);
        }
        return $model;*/
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if (!$model->save()) {
            return array_values($model->getFirstErrors())[0];
        }
        return $model;
    }

    public function actionDelete($id)
    {
        return $this->findModel($id)->delete();
    }

    public function actionView($id)
    {
        return $this->findModel($id);
    }

    protected function findModel($id)
    {
        $modelClass = $this->modelClass;
        if (($model = $modelClass::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
