<?php
namespace frontend\models\cart;

use yii;

//класс корзина для неавторизованных пользователей
class GuestCart extends Cart
{
    protected function _getCounts()
    {
        if (Yii::$app->session->has('cart'))
            return Yii::$app->session->get('cart');
        return [];
    }
    
    //обновляет корзину
    public function update($id,$count)
    {
        parent::update($id, $count);
        Yii::$app->session->set('cart',$this->counts);
    }
    
    //очищает корзину
    public function clear()
    {
        Yii::$app->session->remove('cart');
        parent::clear();
    }
    
    public function _getPriceSum()
    {
        return 0;
    }
}

?>
