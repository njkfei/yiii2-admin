<?php

namespace app\controllers;

use Yii;
use app\models\Customer;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StatusController implements the CRUD actions for Status model.
 */
class CustomerController extends Controller
{
    public $modelClass = 'app\models\Customer';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }


    /**
     * Creates a new Status model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $customer = new Customer;

        $customer->name = 'hello';
        $customer->email = 'world';
        $customer->insert();  // 等同于 $customer->insert();

        return json_encode(array(
            'name' => $customer->$name,
            'email' => $customer->$email,
        ));

    }

    public function actionGet($id){
        $customer = Customer::find()->where(['id' => $id])->one();
        $object = array(
            'id' => $customer->id,
            'name' => $customer->name,
            'email' => $customer->email,
        );
       return  json_encode($object);
    }

    public function actionDe(){
        Customer::deleteAll();
    }
}
