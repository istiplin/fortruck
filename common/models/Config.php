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
    //настройки, которые были извлечены из базы данных
    private static $values=[];
    
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
            'name' => 'Настраиваемый компонент',
            'alias' => 'Alias',
            'value' => 'Значение',
            'description' => 'Описание',
        ];
    }
    
    //возвращает значение из списка настроек по алиасу
    public static function value($alias)
    {
        if (array_key_exists($alias, self::$values))
            return self::$values[$alias];
        
        return self::$values[$alias] = self::findOne(['alias'=>$alias])->value;
    }
    
    //берем из базы все настройки одним запросом
    public static function getValuesAll()
    {
        return self::$values = self::find()->select('value,alias')->indexBy('alias')->asArray()->column();
    }
}