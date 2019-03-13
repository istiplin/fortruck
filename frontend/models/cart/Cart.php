<?php
namespace frontend\models\cart;

use Yii;
use common\models\Config;
use common\models\Product;
use yii\helpers\Html;
use yii\helpers\Json;

//абстрактный класс корзина
abstract class Cart extends \yii\base\Model
{ 
    //указатель на экземпляр класса корзина
    static private $_instance = null;
    
    //количество товаров в корзине ['id товара'=>'количество']
    protected $_counts;
    
    protected $_priceSum;
    
    //фабричный метод (определяет какой класс инициализировать)
    public static function initial()
    {
        if (self::$_instance!==null)
            return self::$_instance;
        
        if (Yii::$app->user->isGuest)
            return self::$_instance = new GuestCart;
        else
            return self::$_instance = new AuthCart;
    }

    //определяет количество товаров в корзине по идентификатору
    public function getCount($id)
    {
        if (isset($this->counts[$id]))
            return $this->counts[$id];
        return 0;
    }
    
    abstract protected function getCounts();
    
    //определяет количество типов товаров в корзине
    public function getTypeCount()
    {
        return count($this->counts);
    }
    
    //возвращает количество всех товаров в корзине
    public function getCountSum()
    {
        return (count($this->counts)?array_sum($this->counts):0);
    }
    
    //возвращает список id товаров в корзине
    public function getListId()
    {
        return array_keys($this->counts);
    }
    
    //возвращает информацию о товарах в корзине
    public function getProductsInfo()
    {
        $coef = Config::value('cost_price_coef');
        $implodedId = implode(',',$this->listId);
        
        $sql = "select
                    p.id,
                    $coef*p.price as custPrice
                from product p
                where p.id in($implodedId) and p.price>0";
        
        return Yii::$app->db->createCommand($sql)->queryAll();
    }
    
    //возвращает стоимость содержимого в корзине без проверки на то, что функция уже выполнялась
    abstract protected function getPriceSum();
    
    //обновляет корзину по идентификатору товара
    public function update($product,$count)
    {
        if (isset($product['id'])){
            $id = $product['id'];
        }
        elseif (strlen($product['number']) AND strlen($product['brandName'])){
            $number = $product['number'];
            $brandName = $product['brandName'];
            $id = Product::getIdByNumberAndBrandName($number,$brandName,$product);
        }
        else {
            throw new \Exception("'id' and ('number' or 'brandName') is not exist");
        }
        
        if (!ctype_digit($count))
        {
            return [
                'status' => 'error',
                'message' => 'Задано неверное количество товаров',
            ];
        }
        
        if ($count==0)
            $message = 'Товар удален из корзины';
        else
            $message = 'Товар обновлен в корзине';

        return [
            'status' => 'success',
            'message' => $message,
            'id' => $id,
        ];
    }
    
    //очищает корзину, например после оформления заказа
    public function clear()
    {
        $this->_counts = [];
    }
    
    public function getCountView(\frontend\models\Product $product)
    {
        if (!$product->isPresent)
            return '-';
        
        $count = 0;
        if ($product->id)
            $count = $this->getCount($product->id);
        
        return "<span class='cart-count-value'>".$count."</span>"; 
    }
    
    public function view(\frontend\models\Product $product)
    {
        if (!$product->isPresent)
            return '-';
        
        $count = 0;
        $productData = [];
        
        //print_r($product); die();
        
        if ($product->id)
        {
            $count = $this->getCount($product->id);
        //    $productData['id'] = $product->id;
        }
        //else
        if(strlen($product->number) AND strlen($product->brandName))
        {
            $productData['number'] = $product->number;
            $productData['brandName'] = $product->brandName;
            $productData['name'] = $product->name;
            $productData['price'] = $product->price;
            $productData['count'] = $product->count;
        }
        else{
            throw new \Exception("'id' and ('number' or 'brandName') is not exist");
        }
        
        return "<div class='add-to-cart'>".
                    Html::button('-', ['class'=>'minus-button']).
                    Html::input('text', 'cart[count]', $count,[
                                                    'size'=>1,
                                                    'class'=>'cart-count',
                                                    'data-product'=>Json::encode($productData),
                                            ]).
                    Html::button('+', ['class'=>'plus-button']).
                    Html::submitButton('',['class'=>'cart-button',
                                            'data-call-cart-list'=>(\Yii::$app->controller->action->id=='search')?1:0]).
                "</div>";
    }
}
