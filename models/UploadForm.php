<?php
/**
 * Created by PhpStorm.
 * User: njp
 * Date: 2016/4/14
 * Time: 14:55
 */

namespace app\models;
use yii\base\Model;
use yii\web\UploadedFile;
use Aws;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;
    public $imageName;
    public $key;

    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
            [['imageName','key'],'string']
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
          //  $this->imageFile->saveAs('uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);

            $this->imageFile->saveAs('c:/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);

            // 写mysql
            $this->updateDb();

            $this->updateAwsS3();



            // 更新缓存
            $this->updateRedis();

            return true;
        } else {
            return false;
        }
    }

    // 更新数据库
    public function updateDb(){
        $posts = Yii::$app->db->createCommand('INSERT INTO theme() values()')
            ->queryAll();
        return $posts;
    }

    // 更新 aws 缓存
    public function updateAwsS3(){
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

        try {
            $result = $client->putObject(array(
                'Bucket' => 'theme.kkk',
                'Key' =>  $this->imageFile->baseName . '.' . $this->imageFile->extension,
                'SourceFile' =>'c:/' . $this->imageFile->baseName . '.' . $this->imageFile->extension,
                'ContentType' => 'text/plain',
                //      'ACL' => 'public-read',
            ));
        } catch (S3Exception $e) {
            echo $e->getMessage() . "\n";
        }
    }
    // 更新redis缓存
    public function updateRedis(){

    }


}