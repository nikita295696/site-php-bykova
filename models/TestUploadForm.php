<?php
/**
 * Created by PhpStorm.
 * User: ПК
 * Date: 31.08.2018
 * Time: 5:37
 */

namespace app\models;


use PHPUnit\Util\Test;
use yii\base\Model;
use yii\web\UploadedFile;

class TestUploadForm extends Model
{
    public $name;

    /**
     * @var UploadedFile[]
     */
    public $imageFiles;

    public function rules()
    {
        return [
            [['name', 'imageFiles'], 'required'],
            [['imageFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'txt', 'maxFiles' => 10],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $modelTest = new Tests();
            $modelTest->name = $this->name;
            if($modelTest->save()){
                foreach ($this->imageFiles as $file) {


                    $category = new Categories();
                    $category->name = $file->baseName;
                    $category->testId = $modelTest->id;
                    if ($category->save()) {
                        $fileName = 'uploads/' . $file->baseName . '.' . $file->extension;
                        $file->saveAs($fileName);
                        mb_internal_encoding("UTF-8");

                        $contents = file($fileName);
                        foreach ($contents as $question) {
                            $modelQuestion = new Questions();
                            $modelQuestion->question = $question;
                            $modelQuestion->categoryId = $category->id;
                            $modelQuestion->save();
                        }
                    } else {
                        return false;
                    }
                    //$file->saveAs('uploads/' . $file->baseName . '.' . $file->extension);
                }
        }
            return true;
        } else {
            return false;
        }
    }
}