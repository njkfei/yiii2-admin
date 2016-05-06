<?php
/**
 * Created by PhpStorm.
 * User: njp
 * Date: 2016/4/13
 * Time: 18:36
 */
namespace app\models;

use \yii\db\ActiveRecord;
/**
 * Country Model
 *
 * @author Budi Irawan <deerawan@gmail.com>
 */
class Country extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'country';
    }

    /**
     * @inheritdoc
     */
    public static function primaryKey()
    {
        return ['code'];
    }

    /**
     * Define rules for validation
     */
    public function rules()
    {
        return [
            [['code', 'name', 'population'], 'required']
        ];
    }
}