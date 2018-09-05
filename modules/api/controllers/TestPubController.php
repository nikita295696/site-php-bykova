<?php
/**
 * Created by PhpStorm.
 * User: ПК
 * Date: 31.08.2018
 * Time: 6:39
 */

namespace app\modules\api\controllers;

use app\models\Tests;
use yii\rest\ActiveController;

class TestPubController extends ActiveController
{
    public $modelClass = 'app\models\Tests';

    private function getTestAndQuestions($id)
    {
        if(($modelTest = Tests::findOne($id)) !== null)
        {
            $json = [];

            $json['id'] = $modelTest->id;
            $json['name'] = $modelTest->name;
            foreach ($modelTest->categories as $category)
            {
                $cat = [];
                $cat['name'] = $category->name;
                foreach ($category->questions as $question){
                    $quest = [];
                    $quest['name'] = $question->question;
                    $cat['questions'][] = $quest;
                }
                $json['categories'][] = $cat;
            }
            return $json;
        }
        return false;
    }

    public function actionGenerateTest($id, $quest_count = 2)
    {
        if(empty($quest_count) || !is_numeric($quest_count) || $quest_count < 1){
            $quest_count = 2;
        }

        $json = [];

        if(($totalTest = $this->getTestAndQuestions($id)) !== false) {
            $json['name'] = $totalTest['name'];
            foreach ($totalTest['categories'] as $category){
                $cat = [];
                $cat['name'] = $category['name'];
                $questions = $category['questions'];

                shuffle($questions);
                $newQuestion = [];
                for($i = 0; $i < $quest_count; $i++){
                    $newQuestion[] = array_shift($questions);
                }
                $cat['questions'] = $newQuestion;
                $json['categories'][] = $cat;
            }
        }

        return $json;
    }

    public function actionAllTests()
    {
        $tests = Tests::find()->all();
        $json = [];
        foreach ($tests as $test) {
            if(($all = $this->getTestAndQuestions($test['id'])) !== false){
                $json[] = $all;
            }
        }
        return $json;
    }

    public function actionTestsNames()
    {
        $tests = Tests::find()->all();
        $json = [];
        foreach ($tests as $test) {
            if(($all = $this->getTestAndQuestions($test['id'])) !== false){
                $json[] = [ 'name' => $all['name'], 'id'=> $all['id']];
            }
        }
        return $json;
    }
}