<?php
namespace frontend\models\cart;

use Yii;
use common\models\Config;
use common\models\Product;
use frontend\models\RemoteOffersProducts;

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
            return $this->counts[$id]['cartCount'];
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
        if (count($this->counts)==0)
            return 0;
        
        $countSum = 0;
        foreach ($this->counts as $count)
        {
            if ($count['productCount']>0)
                $countSum+=$count['cartCount'];
        }  
        return $countSum;
        
        //return (count($this->counts)?array_sum($this->counts):0);
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
                    $coef*p.price as custPrice,
                    count
                from product p
                where p.id in($implodedId) and p.price>0 and p.count>0";
        
        return Yii::$app->db->createCommand($sql)->queryAll();
    }
    
    //возвращает стоимость содержимого в корзине без проверки на то, что функция уже выполнялась
    abstract protected function getPriceSum();
    
    public function getPriceSumView(){
        return sprintf("%01.2f", $this->priceSum).' руб.';
    }
    
    //обновляет корзину
    public function update($product,$count)
    {
        if (strlen($product['number']) AND strlen($product['brandName']))
            $productInfo = Product::getInfoByNumberAndBrandName($product['number'],$product['brandName']);
        else
            throw new \Exception("'number' or 'brandName' is not exist");
        
        if (!ctype_digit($count))
            return [
                'status' => 'error',
                'message' => 'Задано неверное количество товаров',
            ];
        
        if ($productInfo->count<$count)
            return [
                'status' => 'error',
                'message' => 'Товара с таким количеством нет!',
            ];
        
        if ($count==0)
            $message = 'Товар удален из корзины';
        else
            $message = 'Товар обновлен в корзине';

        return [
            'status' => 'success',
            'message' => $message,
            'id' => $productInfo->id,
        ];
    }
    
    //очищает корзину, например после оформления заказа
    public function clear($idList)
    {
        foreach ($idList as $id)
            unset($this->_counts[$id]);
    }
}