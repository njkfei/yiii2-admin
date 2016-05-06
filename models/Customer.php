<?php
/**
 * Created by PhpStorm.
 * User: njp
 * Date: 2016/4/14
 * Time: 22:21
 */

namespace app\models;

use yii\redis\ActiveRecord;

class Customer extends ActiveRecord
{
    public function attributes()
    {
        return ['id', 'name','email'];
    }

    public function json($customer){
        return json_encode(array(
            'name' => $customer->$name,
            'email' => $customer->$email,
        ));
    }
}