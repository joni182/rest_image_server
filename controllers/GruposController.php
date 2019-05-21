<?php

namespace app\controllers;

use app\models\Grupos;
use app\models\UploadFiles;
use Yii;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * GruposController implements the CRUD actions for Grupos model.
 */
class GruposController extends ActiveController
{
    public $modelClass = 'app\models\Grupos';
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'create' => ['POST'],
                    'update' => ['PUT'],
                    'delete' => ['DELETE'],
                ],
            ],
        ];
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['view']);
        unset($actions['create']);
        unset($actions['update']);
        return $actions;
    }


    /**
     * Lists all Grupos models.
     * @return mixed
     */
    public function actionIndex()
    {
        return null;
        Grupos::findAll();
    }

    /**
     * Displays a single Grupos model.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->findByName($id);
    }

    /**
     * Creates a new Grupos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        dd(UploadedFile::getInstancesByName('upfile'));

        $model = new Grupos();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->response->statusCode = 201;
            return $model;
        }

        throw new \yii\web\HttpException(500, 'The requested Item could not be found.');
    }

    /**
     * Updates an existing Grupos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        // necesario para que $_FILES se carge cuando la request es PUT
        Yii::$app->request->getBodyParams();
        $uploadFiles = new UploadFiles();



        $uploadFiles->imageFiles = UploadedFile::getInstancesByName('upfile');


        $uploadFiles->upload();

        return $uploadFiles->nombres;
        $model = $this->findByName($id);
    }

    /**
     * Deletes an existing Grupos model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Grupos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Grupos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Grupos::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    protected function findByName($nombre)
    {
        if (($model = Grupos::findOne(['nombre' => $nombre])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
