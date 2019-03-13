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
    private static $values = [];
    private static $isValuesAll = false;
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
        
        if ($alias == 'cost_price_coef')
        {
            $value = self::$values[$alias] = 1.0 + self::value('cost_price_percent')/100;
            return $value;
        }
        
        $value = self::$values[$alias] = self::findOne(['alias'=>$alias])->value;
        
        return $value;
    }
    
    //берем из базы все настройки одним запросом
    public static function getValuesAll()
    {
        if (self::$isValuesAll)
            return self::$values;
        
        self::$isValuesAll = true;
        self::$values = self::find()->select('value,alias')->indexBy('alias')->asArray()->column();
        
        return self::$values;

    }
}