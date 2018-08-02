<?php
namespace frontend\models\bag;

use yii;
use yii\data\SqlDataProvider;

use common\models\Product;

//класс корзина для неавторизованных пользователей
class GuestBag extends Bag
{
    private $_bag=[];

    public function  __construct()
    {
        if (Yii::$app->session->has('bag'))
            $this->_bag = Yii::$app->session->get('bag');
    }
    
    //обновляет корзину
    public function update($id,$count)
    {
        $message = '';
        if ($count==0)
        {
            if(array_key_exists($id, $this->_bag))
            {
                unset($this->_bag[$id]);
                $this->_message = [$id=>"<p class='text-danger'>Товар удален из корзины</p>"];
            }
        }
        else
        {
            $this->_bag[$id] = $count;
            $this->_message = [$id=>"<p class='text-success'>Количество тваров в корзине $count</p>"];
        }
        
        Yii::$app->session->set('bag',$this->_bag);
    }
    
    //определяет количество товаров в корзине по идентификатору
    public function count($id)
    {
        if (isset($this->_bag[$id]))
            return $this->_bag[$id];
        return 0;
    }
    
    //определяет количество типов товаров в корзине
    public function getTypeCount()
    {
        return count($this->_bag);
    }

    public function getProductIds()
    {
        return array_keys($this->_bag);
    }
    
    //очищает корзину
    public function clear()
    {
        Yii::$app->session->remove('bag');
        $this->_bag = [];
    }

}

?>
