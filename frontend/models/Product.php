<?php
namespace frontend\models;

use Yii;
use common\models\Config;
use yii\helpers\Html;
use frontend\models\cart\Cart;

//класс для предоставления данных о товаре для покупателя
class Product extends \yii\base\Model
{
    private $_id;
    public $number;
    public $brandName;
    public $name;
    public $count;
    public $price;
    public $is_visible=1;
    public $original_id;
    
    private static $_listId;
    
    public function attributeLabels()
    {
        $percent = Config::value('cost_price_percent');
        return [
            'id' => 'ID',
            'number' => 'Артикул',
            'name' => 'Наименование',
            'original_id' => 'Оригинальный номер',
            'brand_id' => 'Brand ID',
            'brandName' => 'Бренд',
            'price' => 'Себестоимость',
            'custPrice' => 'Цена для покупателя (+'.$percent.'%)',
            'custPriceView' => 'Цена, руб.',
            'originalName' => 'Оригинальный номер',
            'countView' => 'Количество',
            'cartCountView' => 'В корзине',
            'cartView' => 'Заказ',
        ];
    }
    
    public function __construct($model)
    {
        foreach ($model as $key=>$value)
            $this->$key = $value;
    }

    public static function setListId($inBrandNumberStr)
    {
        if (strlen($inBrandNumberStr)==0)
            return;
        
        self::$_listId = [];
        $sql = 'SELECT 
                    p.id,
                    p.number,
                    b.name brandName
                FROM `product` p
                left join brand b on b.id = p.brand_id
                where (b.name, p.number) in('.$inBrandNumberStr.')';
        $res = \Yii::$app->db->createCommand($sql)->queryAll();
        
        foreach ($res as $record)
        {
            $brandName = mb_strtoupper($record['brandName']);
            self::$_listId[$brandName][$record['number']] = $record['id'];
        }
    }
    
    public function setId($value)
    {
        $this->_id = $value;
    }
    
    public function getId()
    {
        if ($this->_id !== null)
            return $this->_id;
        
        if(count(self::$_listId)==0 OR $this->number === null OR $this->brandName === null)
            return null;
        
        if (!isset(self::$_listId[$this->brandName][$this->number]))
            return null;

        return self::$_listId[$this->brandName][$this->number];
    }
    
    //отображает цену для покупателя
    public function getCustPrice()
    {
        $coef = Config::value('cost_price_coef');
        return number_format(round($coef*$this->price,2),2,'.','');
    }
    
    //определяет считать ли нам, что товар в наличии
    public function getIsAvailable()
    {
        //считаем, что товар в наличии, если определена цена и количество
        return $this->price AND $this->count;
    }
    
    //возвращает цену, которая будет отображаться в представлении для покупателя
    public function getCustPriceView($checkPresent = true)
    {
        if (!$this->isAvailable)
        {
            if ($checkPresent)
                return '-';
            else
                return 'Нет в наличии';
        }   
        
        if (Yii::$app->user->isGuest)
            return Html::a('Цена по запросу','',['class'=>'request-price-button','data-number'=>$this->number,'data-brand-name'=>$this->brandName,'data-toggle'=>'modal','data-target'=>'#request-price-modal']);
        else
            return $this->custPrice;
    }
    
    //возвращает количество товаров, которая будет отображаться в представлении для покупателя
    public function getCountView()
    {
        if($this->isAvailable)
        {
            $maxCount = 10;
            if ($this->count>$maxCount)
                return '>'.$maxCount;
            return $this->count;
        }
        else
            return 'Нет в наличии';
    }
    
    public function getCartCountView($checkPresent = true)
    {
        if ($checkPresent AND !$this->isAvailable)
            return '-';
        
        $count = 0;
        if ($this->id)
            $count = Cart::initial()->getCount($this->id);
        
        return "<span class='cart-count-value'>".$count."</span>"; 
    }
    
    public function getCartView($checkPresent = true)
    {
        if ($checkPresent AND !$this->isAvailable)
            return '-';
        
        $count = 0;
        $productData = [];
        
        if ($this->id)
        {
            $count = Cart::initial()->getCount($this->id);
        //    $productData['id'] = $this->id;
        }
        //else
        if(strlen($this->number) AND strlen($this->brandName))
        {
            $productData['number'] = $this->number;
            $productData['brandName'] = $this->brandName;
            //$productData['name'] = $this->name;
            //$productData['price'] = $this->price;
            //$productData['count'] = $this->count;
        }
        else{
            throw new \Exception("'id' and ('number' or 'brandName') is not exist");
        }
        
        return "<div class='add-to-cart'>".
                    Html::button('-', ['class'=>'minus-button']).
                    Html::input('text', 'cart[count]', $count,[
                                                    'size'=>1,
                                                    'class'=>'cart-count',
                                                    'data-product'=>\yii\helpers\Json::encode($productData),
                                            ]).
                    Html::button('+', ['class'=>'plus-button']).
                    Html::submitButton('',['class'=>'cart-button cart-img',
                                            'data-call-cart-list'=>(\Yii::$app->controller->action->id=='search')?1:0]).
                "</div>";
    }
}
?>