<?php
namespace frontend\models\cart;

use Yii;
use common\models\Cart as ARCart;
use common\models\Config;

//класс корзина для авторизованных пользователей
class AuthCart extends Cart
{
    public function update($id,$count)
    {
        $cart = ARCart::findOne(['user_id'=>Yii::$app->user->identity->id,'product_id'=>$id]);
        if ($cart)
        {
            if ($count==0)
            {
                $cart->delete();
                $this->_message = [$id=>"<p class='text-danger'>Товар удален из корзины</p>"];
            }
            else
            {
                $cart->count=$count;
                $cart->save();
                $this->_message = [$id=>"<p class='text-success'>Количество тваров в корзине $count</p>"];
            }
        }
        elseif ($count!=0) 
        {
            $cart = new ARCart();
            $cart->user_id = Yii::$app->user->identity->id;
            $cart->product_id = $id;
            $cart->count = $count;
            $cart->save();
            $this->_message = [$id=>"<p class='text-success'>Количество тваров в корзине $count</p>"];
        }
    }
    
    //определяет количество типов товаров в корзине
    public function getTypeCount()
    {
        if ($this->_typeCount!==null)
            return $this->_typeCount;
        
        return $this->_typeCount = ARCart::find(['user_id'=>Yii::$app->user->identity->id])->count();
    }
    
    public function clear()
    {
        ARCart::deleteAll(['user_id'=>Yii::$app->user->identity->id]);
    }
   
    //возвращает информацию о товарах в корзине
    public function getProductsInfo()
    {
        $coef = Config::value('cost_price_coefficient');
        
        $sql = "select
                    p.id,
                    p.cost_price*$coef as price,
                    b.count
                from product p
                join cart b on b.product_id = p.id
                where b.user_id=".Yii::$app->user->identity->id;
        
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        
        $productsInfo = [];
        foreach($res as $rec)
            $productsInfo[$rec['id']] = $rec;
        
        return $productsInfo;
    }
}
