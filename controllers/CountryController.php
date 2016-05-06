<?php
/**
 * Created by PhpStorm.
 * User: njp
 * Date: 2016/4/13
 * Time: 18:37
 */

namespace app\controllers;


use yii\rest\ActiveController;

/**
 * Country Controller API
 *
 * @author Budi Irawan <deerawan@gmail.com>
 */
class CountryController extends ActiveController
{
    public $modelClass = 'api\models\Country';
}