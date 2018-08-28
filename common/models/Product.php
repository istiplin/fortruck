<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string $number
 * @property string $name
 * @property int $original_id
 * @property string $producer_name
 * @property string $cost_price
 *
 * @property Cart[] $carts
 * @property User[] $users
 * @property OrderItem[] $orderItems
 * @property Order[] $orders
 */ 
class Product extends \yii\db\ActiveRecord
{
    private $_originalNumber;
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
            [['number', 'original_id'], 'required'],
            [['original_id'], 'integer'],
            [['cost_price'], 'number'],
            [['number', 'producer_name'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 100],
            [['number'], 'unique'],
            [['number','name','producer_name','cost_price'],'trim'],
            ['originalNumber','safe'],
        ];
    } 
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'number' => 'Номер аналога',
            'name' => 'Наименование',
            'original_id' => 'Оригинальный номер',
            'producer_name' => 'Производитель',
            'cost_price' => 'Себестоимоть',
            'price' => 'Цена',
            'originalNumber' => 'Оригинальный номер',
        ];
    }
    
    public function beforeValidate() {
        if (parent::beforeValidate())
        {
            //если было установлено значение оригинального номера
            if (isset($this->_originalNumber))
            {
                $originalNumber = $this->_originalNumber;
                
                //определяем его id
                $original_id = static::findOne(['number' => $originalNumber])->id;

                //если id определен
                if ($original_id)
                {
                    //сохраняем id
                    $this->original_id = $original_id;
                    return true;
                }
                
                //если номер аналога является оригинальным
                if ($originalNumber == $this->number)
                {
                   
                    $this->original_id = 0;
                    return true;
                }

                $this->addError('originalNumber',"{$this->attributeLabels()['originalNumber']} не существует или не совпадает с полем {$this->attributeLabels()['number']}");
                return false;
                
            }
        }
        return true;
    }
    
    public function __set($name,$value)
    {
        if ($name === 'originalNumber')
            $this->_originalNumber = $value;
        else
            parent::__set ($name, $value);
    }
    
    public function save($runValidation = true, $attributeNames = null) {
        if (parent::save($runValidation, $attributeNames))
        {
            if ($this->original_id == 0 AND $this->id != 0)
            {
                $this->original_id = $this->id;
                return $this->save($runValidation, $attributeNames);
            }
            return true;
        }
        return false;
    }
    
    /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getCarts() 
    { 
        return $this->hasMany(Cart::className(), ['product_id' => 'id']);
    } 

    /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getUsers() 
    { 
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('cart', ['product_id' => 'id']);
    } 

    /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getOrderItems() 
    { 
        return $this->hasMany(OrderItem::className(), ['product_id' => 'id']);
    } 

    /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getOrders() 
    { 
        return $this->hasMany(Order::className(), ['id' => 'order_id'])->viaTable('order_item', ['product_id' => 'id']);
    }
    
    public function getOriginal()
    {
        return $this->hasOne(self::className(), ['id'=>'original_id']);
    }
    
    public function getOriginalNumber()
    {
        //если было установлено значение оригинального номера
        if (isset($this->_originalNumber))
            //выводим значение установленного оригинального номера
            return $this->_originalNumber;
        
        //иначе выводим оригинальный номер
        return $this->original->number;
    }
    
    public function getPrice()
    {
        return sprintf("%01.2f", $this->cost_price * Config::value('cost_price_coefficient'));
    }
}
