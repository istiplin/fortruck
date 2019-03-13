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
 * @property int $brand_id
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
    //private $_originalName;
    //private $_brandName;
    private $_custPrice;
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
            [['number', 'brand_id'], 'required'],
            [['original_id', 'brand_id', 'count', 'is_visible'], 'integer'],
            [['price'], 'number'],
            [['price_change_time'], 'safe'],
            [['number'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 100],
            [['number','name','price'],'call_mb_trim'],
            [['number', 'brand_id'], 'unique', 'targetAttribute' => ['number', 'brand_id']],
            [['brand_id'], 'exist', 'skipOnError' => true, 'targetClass' => Brand::className(), 'targetAttribute' => ['brand_id' => 'id']],
            [['original_id'], 'exist', 'skipOnError' => true, 'targetClass' => OriginalProduct::className(), 'targetAttribute' => ['original_id' => 'id']],
            [['is_visible'], 'default', 'value'=>1],
            [['originalName','priceView','brandName','custPrice'],'safe'],
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
        $percent = Config::value('cost_price_percent');
        
        return [
            'id' => 'ID',
            'number' => 'Номер аналога',
            'name' => 'Наименование',
            'original_id' => 'Оригинальный номер',
            'brand_id' => 'Brand ID',
            'brandName' => 'Бренд',
            'price' => 'Себестоимость',
            'custPrice' => 'Цена для покупателя (+'.$percent.'%)',
            'originalName' => 'Оригинальный номер',
            'count' => 'Количество',
            'countView' => 'Количество',
            'price_change_time' => 'Время изменения цены',
            'is_visible' => 'Отображать в магазине',
        ];
    }
    
    public static function getIdByNumberAndBrandName($number,$brandName,$saveData=null)
    {
        $brandId = Brand::getIdByName($brandName,true);
        
        $product = self::findOne(['number'=>$number,'brand_id'=>$brandId]);
        
        if ($saveData)
        {
            unset($saveData['brandName']);
            if (!$product)
            {
                $product = new self;
                $product->number = $number;
                unset($saveData['number']);
                
                $product->brand_id = $brandId;
                unset($saveData['brand_id']);
            }
            
            foreach ($saveData as $key=>$value)
                $product->$key = $value;
            /*
            $product->name = $saveData['name'];
            $product->count = $saveData['count'];
            $product->price = $saveData['price'];
            
            if (isset($saveData['original_id']))
                $product->original_id = $saveData['original_id'];
             * 
             */
            
            $product->save();
            return $product->id;
        }
        
        return $product->id;
    }
    
    public function setCustPrice($value)
    {
        $this->_custPrice = $value;
    }
    
    public function getCustPrice()
    {
        return $this->_custPrice;
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
    
    /*
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
     * 
     */
    
    /*
    public function __set($name,$value)
    {
        //if ($name === 'originalNumber')
        //    $this->_originalNumber = $value;
        //else
            if ($name === 'price')
        {
            //если цена определена
            if ($value)
                //меняем время изменения цены
                //integration_time
                $this->price_change_time = new Expression('NOW()');
            parent::__set($name, $value);
        }
        else
            parent::__set($name, $value);
    }
     * 
     */
    
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
    
    /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getBrand() 
    { 
        return $this->hasOne(Brand::className(), ['id' => 'brand_id']);
    } 
    
    /*
    public function setBrandName($value)
    {
        $this->_brandName = $value;
    }
     * 
     */
    
    public function getBrandName()
    {
        //иначе выводим оригинальный номер
        if ($this->brand->name)
            return $this->brand->name;
        else
            return $this->_brandName;
    }
    
    public function getOriginalProduct()
    {
        //return $this->hasOne(self::className(), ['id'=>'original_id']);
        return $this->hasOne(OriginalProduct::className(), ['id' => 'original_id']);
    }
    
    public function getOriginalName()
    {
        //если было установлено значение оригинального номера
        //if (isset($this->_originalName))
            //выводим значение установленного оригинального номера
            //return $this->_originalName;
        
        //иначе выводим оригинальный номер
        return $this->originalProduct->name;
    }
}
