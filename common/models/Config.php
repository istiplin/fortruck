<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "config".
 *
 * @property string $name
 * @property string $alias
 * @property string $value
 * @property string $description
 */
class Config extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'config';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'alias', 'value'], 'required'],
            [['name'], 'string', 'max' => 50],
            [['alias', 'value'], 'string', 'max' => 30],
            [['description'], 'string', 'max' => 200],
            [['name'], 'unique'],
            [['alias'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'alias' => 'Alias',
            'value' => 'Value',
            'description' => 'Description',
        ];
    }
    
    public static function value($alias)
    {
        return self::findOne(['alias'=>$alias])->value;
    }
}
