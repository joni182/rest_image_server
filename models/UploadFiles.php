<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadFiles extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $imageFiles;
    public $nombres = [];
    public function rules()
    {
        return [
            [['imageFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg', 'maxFiles' => 4],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            foreach ($this->imageFiles as $key => $file) {
                $nombreGenerado = str_replace('.', '', microtime(true) . '') . $key;

                copy($file->tempName, Yii::getAlias('@app/uploads/') . $nombreGenerado . $file->extension);

                $this->nombres[] = $nombreGenerado;
            }
            return true;
        }
        return false;
    }
}
