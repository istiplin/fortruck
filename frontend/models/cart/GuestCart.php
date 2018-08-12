<?php
namespace frontend\models\cart;

use yii;
use yii\data\SqlDataProvider;

use common\models\Product;

//класс корзина для неавторизованных пользователей
class GuestCart extends Cart
{
    private $_cart=[];

    public function  __construct()
    {
        if (Yii::$app->session->has('cart'))
            $this->_cart = Yii::$app->session->get('cart');
    }
    
    //обновляет корзину
    public function update($id,$count)
    {
        $message = '';
        if ($count==0)
        {
            if(array_key_exists($id, $this->_cart))
            {
                unset($this->_cart[$id]);
                $this->_message = [$id=>"<p class='text-danger'>Товар удален из корзины</p>"];
            }
        }
        else
        {
            $this->_cart[$id] = $count;
            $this->_message = [$id=>"<p class='text-success'>Количество тваров в корзине $count</p>"];
        }
        
        Yii::$app->session->set('cart',$this->_cart);
    }
    
    //определяет количество товаров в корзине по идентификатору
    public function count($id)
    {
        if (isset($this->_cart[$id]))
            return $this->_cart[$id];
        return 0;
    }
    
    //определяет количество типов товаров в корзине
    public function getTypeCount()
    {
        return count($this->_cart);
    }

    public function getProductIds()
    {
        return array_keys($this->_cart);
    }
    
    //очищает корзину
    public function clear()
    {
        Yii::$app->session->remove('cart');
        $this->_cart = [];
    }
    
    //возвращает информацию о товарах в корзине
    public function getProductsInfo()
    {
        $products_id = array_keys($this->_cart);
        $productsInfo = Product::find()->select('price, id')->where('id in('.implode(',',$products_id).')')->indexBy('id')->asArray()->all();
        foreach($this->_cart as $id => $count)
            $productsInfo[$id]['count'] = $count;
        return $productsInfo;
    }

}

?>
