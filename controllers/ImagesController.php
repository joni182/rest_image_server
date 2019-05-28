<?php

namespace app\controllers;

use app\models\Images;
use Yii;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * ImagesController implements the CRUD actions for Images model.
 */
class ImagesController extends ActiveController
{
    public $modelClass = 'app\models\Images';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['DELETE'],
                    'view' => ['GET'],
                    'index' => ['GET'],
                ],
            ],
        ];
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        unset($actions['view']);
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);
        return $actions;
    }

    /**
     * Lists all Images models.
     * @return mixed
     */
    public function actionIndex()
    {
        $images = Images::find()->All();
        $rutes = [];
        foreach ($images as $image) {
            $rutes[] = $image->nombre;
        }
        return $rutes;
    }

    /**
     * Displays a single Images model.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $nombre = $id;
        $model = $this->findByName($nombre);
        $response = \Yii::$app->response;
        $response->format = Response::FORMAT_RAW;
        $response->headers->add('content-type', 'image/jpg');
        $img_data = Yii::$app->fs->read($model->nombreConExtension());
        $response->data = $img_data;
        return $response;
    }

    /**
     * Creates a new Images model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        throw new \yii\web\HttpException(501, 'Error HTTP 501 Not implemented.');
    }

    /**
     * Updates an existing Images model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        // $nombre = $id;
        // $model = $this->findByName($nombre);
        // // necesario para que $_FILES se carge cuando la request es PUT
        // Yii::$app->request->getBodyParams();
        //
        // $model = $this->findByName($nombre);
        //
        // $uploadFiles = new UploadFiles();
        // $uploadFiles->grupo_id = $model->id;
        // $uploadFiles->imageFiles = UploadedFile::getInstancesByName('upfile');
        // $uploadFiles->upload();
        //
        // return $uploadFiles->nombres;
        throw new \yii\web\HttpException(501, 'Error HTTP 501 Not implemented.');
    }

    /**
     * Deletes an existing Images model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $nombre = $id;
        $model = $this->findByName($nombre);

        if (Yii::$app->fs->has($model->nombreConExtension())) {
            Yii::$app->fs->delete($model->nombreConExtension());
        }
        return $model->delete();
    }

    /**
     * Finds the Images model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Images the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Images::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    protected function findByName($nombre)
    {
        if (($model = Images::findOne(['nombre' => $nombre])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
