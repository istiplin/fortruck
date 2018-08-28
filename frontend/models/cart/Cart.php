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
    protected $_counts=[];
    
    private $_priceSum;
    
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
        if (isset($this->_counts[$id]))
            return $this->_counts[$id];
        return 0;
    }
    
    //определяет количество типов товаров в корзине
    public function getTypeCount()
    {
        return count($this->_counts);
    }
    
    //возвращает количество всех товаров в корзине
    public function getCountSum()
    {
        return (count($this->_counts)?array_sum($this->_counts):0);
    }
    
    //возвращает список id товаров в корзине
    public function getListId()
    {
        return array_keys($this->_counts);
    }
    
    //возвращает информацию о товарах в корзине
    public function getProductsInfo()
    {
        $coef = Config::value('cost_price_coefficient');
        $implodedId = implode(',',$this->listId);
        
        $sql = "select
                    p.id,
                    p.cost_price*$coef as price
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
    
    //возвращает стоимость содержимого в корзине без проверки на то, что функция уже выполнялась
    abstract protected function _getPriceSum();
    
    //обновляет корзину по идентификатору товара
    abstract public function update($id,$count);
    
    //очищает корзину, например после оформления заказа
    abstract public function clear();
    

}
