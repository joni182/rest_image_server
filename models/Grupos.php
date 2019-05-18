<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "grupos".
 *
 * @property int $id
 *
 * @property Images[] $images
 */
class Grupos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'grupos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(Images::className(), ['grupo_id' => 'id'])->inverseOf('grupo');
    }
}
