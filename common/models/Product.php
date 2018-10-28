<?php

namespace common\models;

use Yii;

use yii\db\Expression;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string $number
 * @property string $name
 * @property int $original_id
 * @property string $producer_name
 * @property int $count
 * @property string $price
 * @property string $price_change_time
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
            [['original_id', 'count'], 'integer'],
            [['price'], 'number'],
            [['price_change_time'], 'safe'],
            [['number', 'producer_name'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 100],
            [['number'], 'unique'],
            [['number','name','producer_name','price'],'trim'],
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
            'price' => 'Цена, руб.',
            'originalNumber' => 'Оригинальный номер',
            'count' => 'Количество',
            'price_change_time' => 'Время изменения цены',
        ];
    }
    
    public function beforeValidate() {
        //перед проверкой сделаем изменение в поле $this->original_id
        if (parent::beforeValidate())
        {
            //если было установлено значение оригинального номера
            if (isset($this->_originalNumber))
            {
                //определяем его id
                $original_id = static::findOne(['number' => $this->_originalNumber])->id;

                //если id определен
                if ($original_id)
                {
                    //сохраняем id
                    $this->original_id = $original_id;
                }
                //иначе если номер аналога является оригинальным
                elseif ($this->_originalNumber == $this->number)
                {
                   
                    $this->original_id = 0;
                }
                //иначе
                else
                {
                    //добавляем новый товар, который будет являться оригинальным
                    $originalProduct = new self;
                    $originalProduct->originalNumber = $this->_originalNumber;
                    $originalProduct->number = $this->_originalNumber;
                    $originalProduct->save();
                    
                    //затем добавляем текущий товар, указывая id оригинала только что добавленного товара
                    $this->original_id = $originalProduct->id;
                }
            }
        }
        return true;
    }
    
    public function __set($name,$value)
    {
        if ($name === 'originalNumber')
            $this->_originalNumber = $value;
        elseif ($name === 'price')
        {
            //меняем время изменения цены, если цена изменилась и новая цена определена
            if ($this->price !== $value AND $value)
                $this->price_change_time = new Expression('NOW()');
            parent::__set($name, $value);
        }
        else
            parent::__set($name, $value);
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
    
    public function delete()
    {
        //если существуют аналоги удаляемого товара
        if (static::findByCondition(['original_id' => $this->id])->count()>1)
        {
            Yii::$app->session->setFlash('delete_product_error','Нельзя удалить оригинальный товар, пока существуют его аналоги');
            //не удаляем этот товар
            return false;
        }
        
        parent::delete();
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
        return sprintf("%01.2f", $this->price);
    }
}
