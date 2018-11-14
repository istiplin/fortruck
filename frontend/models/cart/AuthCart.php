<?php
namespace frontend\models\cart;

use Yii;
use common\models\Cart as ARCart;
use common\models\Config;

//класс корзина для авторизованных пользователей
class AuthCart extends Cart
{
    //возвращает количество каждого товара в корзине
    protected function _getCounts()
    {
        return ARCart::find()->select(['cart.count','cart.product_id'])
                                    ->joinWith('product')
                                    ->andWhere(['user_id'=>Yii::$app->user->identity->id])
                                    ->andWhere('product.price>0')
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
        parent::update($id, $count);
    }
    
    public function clear()
    {
        ARCart::deleteAll(['user_id'=>Yii::$app->user->identity->id]);
        parent::clear();
    }
    
    protected function _getPriceSum()
    {
        return $priceSum = ARCart::find()
                                    ->joinWith('product')
                                    ->andWhere(['user_id'=>Yii::$app->user->identity->id])
                                    ->andWhere('product.price>0')
                                    ->sum('product.price*cart.count');
    }
}