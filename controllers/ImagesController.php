<?php

namespace app\controllers;

use app\models\Images;
use app\models\ImagesSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;

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
                    'delete' => ['POST'],
                    'view' => ['GET'],
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
     * Lists all Images models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ImagesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
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
        return $this->prepareRespuesta($model);
    }

    /**
     * Creates a new Images model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Images();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
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
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
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

    protected function prepareRespuesta($models)
    {
        if (is_array($models)) {
            foreach ($models as $model) {
                $contents = Yii::$app->fs->read($model->nombreConExtension());
                $nombre = $model->nombre;
                $respuesta = [];
                $respuesta['contents'][$nombre] = base64_encode($contents);
                $respuesta['extension'][$nombre] = $model->extension;
                return $respuesta;
            }
        } else {
            $contents = Yii::$app->fs->read($models->nombreConExtension());
            $nombre = $models->nombre;
            $respuesta = [
                'contents' => [
                    $nombre => base64_encode($contents),
                ],
                'extension' => [
                    $nombre => $models->extension,
                ],
            ];
        }

        return $respuesta;
    }
}
