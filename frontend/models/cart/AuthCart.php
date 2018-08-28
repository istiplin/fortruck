<?php
namespace frontend\models\cart;

use Yii;
use common\models\Cart as ARCart;
use common\models\Config;

//класс корзина для авторизованных пользователей
class AuthCart extends Cart
{
    public function  __construct()
    {
        $this->_counts = ARCart::find()->select(['count','product_id'])
                                    ->where(['user_id'=>Yii::$app->user->identity->id])
                                    ->indexBy('product_id')
                                    ->asArray()
                                    ->column();
    }
    
    public function update($id,$count)
    {
        $cart = ARCart::findOne(['user_id'=>Yii::$app->user->identity->id,'product_id'=>$id]);
        if ($cart)
        {
            if ($count==0)
            {
                $cart->delete();
            }
            else
            {
                $cart->count=$count;
                $cart->save();
            }
        }
        elseif ($count!=0) 
        {
            $cart = new ARCart();
            $cart->user_id = Yii::$app->user->identity->id;
            $cart->product_id = $id;
            $cart->count = $count;
            $cart->save();
        }
    }
    
    public function clear()
    {
        ARCart::deleteAll(['user_id'=>Yii::$app->user->identity->id]);
    }
    
    protected function _getPriceSum()
    {
        return $priceSum = Config::value('cost_price_coefficient')*ARCart::find()
                                                                        ->joinWith('product')
                                                                        ->where(['user_id'=>Yii::$app->user->identity->id])
                                                                        ->sum('product.cost_price*cart.count');
    }
}