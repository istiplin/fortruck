<?php
namespace frontend\models\bag;

use Yii;

//абстрактный класс корзина
abstract class Bag extends \yii\base\Model
{
    //поле для хранения сообщений, которые появляются после обновления корзины
    protected $_message=[];
    
    //количество типов товаров в корзине
    protected $_typeCount;
    
    //обновляет корзину по идентификатору товара
    abstract public function update($id,$count);
    
    //очищает корзину, например после оформления заказа
    abstract public function clear();
    
    //определяет количество типов товаров в корзине
    abstract public function getTypeCount();
    
    //фабричный метод (определяет какой класс инициализировать)
    public static function initial()
    {
        if (Yii::$app->user->isGuest)
            return new GuestBag;
        else
            return new UserBag;
    }
    
    public function getProductIds()
    {
        return [];
    }
    
    //текст сообщения по идентификатору товара после обновления корзины
    public function message($id)
    {
        if (isset($this->_message[$id]))
            return $this->_message[$id];
        return null;
    }
}
