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
    public $grupo_id;
    public $imageFiles;
    public $nombres = [];
    public function rules()
    {
        return [
            [['imageFiles'], 'file', 'skipOnEmpty' => false, 'maxFiles' => 30, 'mimeTypes' => 'image/*'],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            foreach ($this->imageFiles as $key => $file) {
                $grupo_id = $this->grupo_id;
                $nombre = str_replace('.', '', microtime(true) . '') . $key;
                $extension = str_replace('image/', '', $file->type);
                $uri = Yii::getAlias('@app/files/') . $nombre . '.' . $extension;

                if (copy($file->tempName, $uri)) {
                    $image = new Images(compact('grupo_id', 'nombre', 'extension', 'uri'));
                    $image->save();
                    $this->nombres[] = $nombre;
                }
            }
            return true;
        }
        return false;
    }
}
