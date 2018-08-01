<?php

namespace frontend\models;

use yii;
use yii\data\SqlDataProvider;

use common\models\Product;
use common\models\Order;
use common\models\OrderItem;

class BagModel extends \yii\base\Model
{
    private $_bag=[];
    
    public function  __construct()
    {
        if (Yii::$app->session->has('bag'))
            $this->_bag = Yii::$app->session->get('bag');
    }

    //данные о товарах, которые в корзине
    public function getDataProvider()
    {
        $implodeBag = implode(',',array_keys($this->_bag));
        $sql = "select
                    p.id,
                    p.number,
                    p.price,
                    p.name as productName,
                    pr.name as producerName,
                    a.name as analogName
                from product p
                join analog a on a.id = p.analog_id
                join producer pr on pr.id = p.producer_id
                where p.id in($implodeBag)";
/*
        $count = Yii::$app->db->createCommand("select 
                                                    count(*)
                                                from product
                                                where id in($implodeBag)")->queryScalar();
                */
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => $this->count(),
            //'pagination' =>[
            //    'pageSize' => 2,
            //]
        ]);
        
        return $dataProvider;
    }
    
    //обновляет корзину
    public function update($id,$count)
    {
        if ($count==0)
        {
            if(array_key_exists($id, $this->_bag))
            {
                unset($this->_bag[$id]);
                Yii::$app->session->setFlash('message',[$id=>"<p class='text-danger'>Товар удален из корзины</p>"]);
            }
        }
        else
        {
            $this->_bag[$id] = $count;
            Yii::$app->session->setFlash('message',[$id=>"<p class='text-success'>Количество тваров в корзине $count</p>"]);
        }
        
        Yii::$app->session->set('bag',$this->_bag);
    }
    
    //количество номеров товаров в корзине
    public function count($id=null)
    {
        if ($id!==null)
            return $this->_bag[$id];
        else
            return count($this->_bag);
    }
    
    public function message($id)
    {
        return Yii::$app->session->getFlash('message')[$id];
    }
    
    //очищает корзину
    public function clear()
    {
        Yii::$app->session->remove('bag');
        $this->_bag = [];
    }
    
    //возвращет цены на товары в корзине
    public function getProductsInfo()
    {
        $products_id = array_keys($this->_bag);
        $productsInfo = Product::find()->select('price, id')->where('id in('.implode(',',$products_id).')')->indexBy('id')->asArray()->all();
        foreach($this->_bag as $id=> $count)
            $productsInfo[$id]['count'] = $count;
        return $productsInfo;
    }
}

?>
