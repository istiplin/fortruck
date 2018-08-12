<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "company".
 *
 * @property int $id
 * @property string $name
 *
 * @property Product[] $products
 */
class Producer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'producer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 50],
            [['name'], 'unique'],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['producer_id' => 'id']);
    }
    
    public function delete()
    {
        if (Product::find()->where(['producer_id'=>$this->id])->count())
        {
            Yii::$app->session->setFlash('deleteErrorMessage',"<p class='text-danger'>Не удалось удалить запись, т.к. '{$this->name}' присутствует в Списке товаров</p>");
            return false;
        }
        parent::delete();
    }
}
