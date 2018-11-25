<?php

namespace common\models;

use Yii;

use yii\db\Expression;
use yii\helpers\Html;

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
 * @property int $is_visible
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
            [['original_id', 'count', 'is_visible'], 'integer'],
            [['price'], 'number'],
            [['price_change_time'], 'safe'],
            [['number', 'producer_name'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 100],
            [['number','name','producer_name','price'],'call_mb_trim'],
            [['number'], 'unique'],
            [['is_visible'], 'default', 'value'=>1],
            [['originalNumber','priceView'],'safe'],
        ];
    } 
    
    //урезаем пробелы слева и справа функцией mb_trim
    public function call_mb_trim($attr)
    {
        $this->$attr = self::mb_trim($this->$attr);
    }
    
    public static function mb_trim($string, $charlist='\\\\s', $ltrim=true, $rtrim=true) 
    {
        $both_ends = $ltrim && $rtrim; 

        $char_class_inner = preg_replace( 
            array( '/[\^\-\]\\\]/S', '/\\\{4}/S' ), 
            array( '\\\\\\0', '\\' ), 
            $charlist 
        ); 

        $work_horse = '[' . $char_class_inner . ']+'; 
        $ltrim && $left_pattern = '^' . $work_horse; 
        $rtrim && $right_pattern = $work_horse . '$'; 

        if($both_ends) 
        { 
            $pattern_middle = $left_pattern . '|' . $right_pattern; 
        } 
        elseif($ltrim) 
        { 
            $pattern_middle = $left_pattern; 
        } 
        else 
        { 
            $pattern_middle = $right_pattern; 
        } 

        return preg_replace("/$pattern_middle/usSD", '', $string); 
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
            'priceView' => 'Цена, руб.',
            'originalNumber' => 'Оригинальный номер',
            'count' => 'Количество',
            'countView' => 'Количество',
            'price_change_time' => 'Время изменения цены',
            'is_visible' => 'Отображать в магазине',
        ];
    }
    
    public static function findByNumber($number)
    {
        return static::findOne(['number' => self::mb_trim($number)]);
    }
    
    public function getVisibleList()
    {
        return [
            0=>'Нет',
            1=>'Да'
        ];
    }
    
    public function getVisibleName()
    {
        return $this->visibleList[$this->is_visible];
    }
    
    public function beforeValidate() {
        //перед проверкой сделаем изменение в поле $this->original_id
        if (!parent::beforeValidate())
            return false;

        //если не было установлено значение оригинального номера
        if (!isset($this->_originalNumber))
            //не учитываем его
            return true;
        
        //определяем id оригинального номера
        $original_id = static::findByNumber($this->_originalNumber)->id;

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
            //оригинальный товар не будет отображаться в магазине, т.к. он добавляется без ведома пользователя
            $originalProduct->is_visible = 0;
            $originalProduct->save();

            //затем добавляем текущий товар, указывая id оригинала только что добавленного товара
            $this->original_id = $originalProduct->id;
        }

        return true;
    }
    
    public function __set($name,$value)
    {
        if ($name === 'originalNumber')
            $this->_originalNumber = $value;
        elseif ($name === 'price')
        {
            //если цена определена
            if ($value)
                //меняем время изменения цены
                $this->price_change_time = new Expression('NOW()');
            parent::__set($name, $value);
        }
        else
            parent::__set($name, $value);
    }
    
    public function save($runValidation = true, $attributeNames = null) {
        //после успешного сохранения
        if (parent::save($runValidation, $attributeNames))
        {
            //если сохранили оригинальный товар
            if ($this->original_id == 0 AND $this->id != 0)
            {
                //значение поля original_id делаем равным значению автоинкрементного поля id
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
    
    //определяет считать ли нам, что товар в наличии
    public function getIsPresent()
    {
        //считаем, что товар в наличии, если определена цена и количество
        return $this->price AND $this->count;
    }
    
    //возвращает цену, которая будет отображаться в представлении для покупателя
    public function getPriceView()
    {
        if ($this->isPresent)
        {
            if (Yii::$app->user->isGuest)
                return Html::a('Цена по запросу','',['class'=>'request-price-button','data-number'=>$this->number,'data-toggle'=>'modal','data-target'=>'#request-price-modal']);
            else
                return $this->price;
        }
        else
            return '-';
    }
    
    //возвращает количество товаров, которая будет отображаться в представлении для покупателя
    public function getCountView()
    {
        if($this->isPresent)
            return $this->count;
        else
            return 'Нет в наличии';
    }
}
