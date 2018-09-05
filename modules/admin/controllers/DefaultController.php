<?php

namespace app\modules\admin\controllers;

use app\models\Tests;
use app\models\TestUploadForm;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use yii\web\UploadedFile;

/**
 * Default controller for the `admin` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {

        return $this->render('index');
    }

    public function actionTests()
    {
        $tests = Tests::find()->all();
        return $this->render('tests', ['tests'=>$tests]);
    }

    public function actionCreate()
    {
        $model = new TestUploadForm();

        if (Yii::$app->request->isPost && $model->load(Yii::$app->getRequest()->post())) {

            $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
            if ($model->upload()) {
                // file is uploaded successfully
                return $this->redirect(Url::toRoute(['/admin/default/tests']));
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionView($id)
    {
        if(($modelTest = Tests::findOne($id)) === null)
        {
            throw new NotFoundHttpException('The requested page does not exist.');
        }



        //return $this->render('view', ['test' => $modelTest]);

        $json = [];

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

        return $this->render('view', ['test' => $json]);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(Url::toRoute(['/admin/default/index']));
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(Url::toRoute(['/admin/default/index']));
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect(Url::toRoute(['/admin/default/index']));
    }
}
