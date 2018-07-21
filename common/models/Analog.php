<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "analog".
 *
 * @property int $id
 * @property string $name
 * @property string $factory_number
 *
 * @property Product[] $products
 */
class Analog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'analog';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 100],
            [['factory_number'], 'string', 'max' => 50],
            [['name'], 'unique'],
            [['factory_number'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Наименование',
            'factory_number' => 'Заводской номер',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['analog_id' => 'id']);
    }
}
