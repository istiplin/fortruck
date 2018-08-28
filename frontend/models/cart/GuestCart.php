<?php
namespace frontend\models\cart;

use yii;

//класс корзина для неавторизованных пользователей
class GuestCart extends Cart
{
    public function  __construct()
    {
        if (Yii::$app->session->has('cart'))
            $this->_counts = Yii::$app->session->get('cart');
    }
    
    //обновляет корзину
    public function update($id,$count)
    {
        $message = '';
        if ($count==0){
            if(array_key_exists($id, $this->_counts)){
                unset($this->_counts[$id]);
            }
        }
        else{
            $this->_counts[$id] = $count;
        }
        
        Yii::$app->session->set('cart',$this->_counts);
    }
    
    //очищает корзину
    public function clear()
    {
        Yii::$app->session->remove('cart');
        $this->_counts = [];
    }
    
    public function getPriceSum()
    {
        
    }
}

?>
