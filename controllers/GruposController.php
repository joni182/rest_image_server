<?php

namespace app\controllers;

use app\models\Grupos;
use app\models\Images;
use app\models\UploadFiles;
use Yii;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
use yii\web\Response;
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
        unset($actions['delete']);
        return $actions;
    }


    /**
     * Lists all Grupos models.
     * @return mixed
     */
    public function actionIndex()
    {
        return Grupos::findAll();
    }

    /**
     * Displays a single Grupos model.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        // $images = Images::find()->all();
        // dd(file_get_contents($images[0]->uri));
        // $response = \Yii::$app->response;
        // $response->format = Response::FORMAT_RAW;
        // $response->headers->add('content-type', 'image/jpg');
        // $img_data = file_get_contents($images[0]->uri);
        // $response->data = $img_data;
        // return $response;
        $nombre = $id;

        return $this->prepareRespuesta($this->findByName($nombre)->images);
    }

    /**
     * Creates a new Grupos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Grupos(Yii::$app->request->post());


        if ($model->save()) {
            Yii::$app->response->statusCode = 201;
            return $model;
        }

        throw new \yii\web\HttpException(500, 'The requested Item could not be found.');
    }

    /**
     * Updates an existing Grupos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @param mixed $nombre
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $nombre = $id;
        // necesario para que $_FILES se carge cuando la request es PUT
        Yii::$app->request->getBodyParams();

        $model = $this->findByName($nombre);

        $uploadFiles = new UploadFiles();
        $uploadFiles->grupo_id = $model->id;
        $uploadFiles->imageFiles = UploadedFile::getInstancesByName('upfile');
        $uploadFiles->upload();

        return $uploadFiles->nombres;
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
        $nombre = $id;
        $model = $this->findByName($nombre);

        foreach ($model->images as $image) {
            if (Yii::$app->fs->has($image->nombreConExtension())) {
                Yii::$app->fs->delete($image->nombreConExtension());
            }
            $image->delete();
        }
        return $model->delete();
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

    protected function prepareRespuesta($models)
    {
        if (is_array($models)) {
            $respuesta = [];
            foreach ($models as $model) {
                $contents = Yii::$app->fs->read($model->nombreConExtension());
                $nombre = $model->nombre;
                $respuesta['names'][] = $nombre;
                $respuesta['contents'][$nombre] = base64_encode($contents);
                $respuesta['extension'][$nombre] = $model->extension;
            }
        } else {
            $contents = Yii::$app->fs->read($models->nombreConExtension());
            $nombre = $models->nombre;
            $respuesta = [];
            $respuesta['names'][] = $nombre;
            $respuesta['contents'][$nombre] = base64_encode($contents);
            $respuesta['extension'][$nombre] = $model->extension;
        }
        return $respuesta;
    }
}
