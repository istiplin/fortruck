<?php
namespace frontend\models\cart;

use Yii;
use common\models\Config;

//абстрактный класс корзина
abstract class Cart extends \yii\base\Model
{ 
    //указатель на экземпляр класса корзина
    static private $instance = null;
    
    //количество товаров в корзине ['id товара'=>'количество']
    private $_counts;
    
    private $_priceSum;
    
    public function __construct() 
    {
        $this->getCounts();
    }
    
    //фабричный метод (определяет какой класс инициализировать)
    public static function initial()
    {
        if (self::$instance!==null)
            return self::$instance;
        
        if (Yii::$app->user->isGuest)
            return self::$instance = new GuestCart;
        else
            return self::$instance = new AuthCart;
    }

    //определяет количество товаров в корзине по идентификатору
    public function getCount($id)
    {
        if (isset($this->counts[$id]))
            return $this->counts[$id];
        return 0;
    }
    
    public function getCounts()
    {
        if ($this->_counts!==null)
            return $this->_counts;
        
        return $this->_counts = $this->_getCounts();
    }
    
    abstract protected function _getCounts();
    
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
        $implodedId = implode(',',$this->listId);
        
        $sql = "select
                    p.id,
                    p.price
                from product p
                where p.id in($implodedId)";
        
        return Yii::$app->db->createCommand($sql)->queryAll();
    }
    
    //возвращает стоимость содержимого в корзине
    public function getPriceSum()
    {
        if ($this->_priceSum!==null)
            return $this->_priceSum;

        $this->_priceSum = $this->_getPriceSum();
        $this->_priceSum = (($this->_priceSum)?sprintf("%01.2f", $this->_priceSum):sprintf("%01.2f", 0)).' руб.';
        return $this->_priceSum;
    }
    
    //обновляет корзину по идентификатору товара
    public function update($id,$count)
    {
        $message = '';
        if ($count==0){
            if(array_key_exists($id, $this->counts)){
                unset($this->_counts[$id]);
            }
        }
        else{
            $this->_counts[$id] = $count;
        }
    }
    
    //возвращает стоимость содержимого в корзине без проверки на то, что функция уже выполнялась
    abstract protected function _getPriceSum();
    
    //очищает корзину, например после оформления заказа
    public function clear()
    {
        $this->_counts = [];
    }
    

}
