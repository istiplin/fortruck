<?php
namespace frontend\models\cart;

use Yii;
use common\models\Cart as ARCart;
use common\models\Config;

//класс корзина для авторизованных пользователей
class AuthCart extends Cart
{
    //возвращает количество каждого товара в корзине
    protected function getCounts()
    {
        if ($this->_counts!==null)
            return $this->_counts;
        
        return $this->_counts = ARCart::find()->select(['cart.count','cart.product_id'])
                                    ->joinWith('product')
                                    ->andWhere(['user_id'=>Yii::$app->user->identity->id])
                                    ->andWhere('product.price>0')
                                    ->andWhere('product.count>0')
                                    ->indexBy('product_id')
                                    ->asArray()
                                    ->column();
    }
    
    public function update($product,$count)
    {
        $res = parent::update($product, $count);
        if ($res['status']=='error')
            return $res;
        
        $id = $res['id'];
        
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
        
        $res['moneySumm'] = $this->priceSum;
        $res['qty'] = $this->countSum;
        
        return $res;
    }
    
    public function clear()
    {
        ARCart::deleteAll(['user_id'=>Yii::$app->user->identity->id]);
        parent::clear();
    }
    
    public function getPriceSum()
    {
        if ($this->_priceSum!==null)
            return $this->_priceSum;
        
        $coef = Config::value('cost_price_coef');
        $priceSum = ARCart::find()
                                    ->joinWith('product')
                                    ->andWhere(['user_id'=>Yii::$app->user->identity->id])
                                    ->andWhere('product.price>0')
                                    ->andWhere('product.count>0')
                                    ->sum("ROUND($coef*product.price,2)*cart.count");
                                    //->sum("product.price*cart.count");
        
        return $this->_priceSum = sprintf("%01.2f", $priceSum).' руб.';
    }
}