<?php

namespace app\models;

/**
 * This is the model class for table "images".
 *
 * @property int $id
 * @property int $grupo_id
 * @property string $nombre
 * @property string $extension
 * @property string $uri
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Grupos $grupo
 */
class Images extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'images';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['grupo_id'], 'default', 'value' => null],
            [['grupo_id'], 'integer'],
            [['nombre', 'extension', 'uri'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['nombre', 'extension', 'uri'], 'string', 'max' => 255],
            [['grupo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Grupos::className(), 'targetAttribute' => ['grupo_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'grupo_id' => 'Grupo ID',
            'nombre' => 'Nombre',
            'extension' => 'Extension',
            'uri' => 'Uri',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupo()
    {
        return $this->hasOne(Grupos::className(), ['id' => 'grupo_id'])->inverseOf('images');
    }

    public function nombreConExtension()
    {
        return $this->nombre . '.' . $this->extension;
    }
}
