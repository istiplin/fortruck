<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string $number
 * @property string $name
 * @property int $analog_id
 * @property int $producer_id
 *
 * @property Producer $producer
 * @property Analog $analog
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['number'], 'required'],
            [['analog_id', 'producer_id'], 'integer'],
            [['number'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 255],
            [['number'], 'unique'],
            [['producer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Producer::className(), 'targetAttribute' => ['producer_id' => 'id']],
            [['analog_id'], 'exist', 'skipOnError' => true, 'targetClass' => Analog::className(), 'targetAttribute' => ['analog_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'number' => 'Номер',
            'name' => 'Наименование',
            'analog_id' => 'Аналог',
            'producer_id' => 'Производитель',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducer()
    {
        return $this->hasOne(Producer::className(), ['id' => 'producer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnalog()
    {
        return $this->hasOne(Analog::className(), ['id' => 'analog_id']);
    }
}