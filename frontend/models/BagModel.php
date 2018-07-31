<?php

namespace frontend\models;

use yii;
use yii\data\SqlDataProvider;

use common\models\Product;
use common\models\Order;

class BagModel extends \yii\base\Model
{
    private $_bag=[];
    private $_message=[];
    
    public function  __construct()
    {
        if (Yii::$app->session->has('bag'))
            $this->_bag = Yii::$app->session->get('bag');
    }

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

        $count = Yii::$app->db->createCommand("select 
                                                    count(*)
                                                from product
                                                where id in($implodeBag)")->queryScalar();
                
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => $count,
            //'pagination' =>[
            //    'pageSize' => 2,
            //]
        ]);
        
        return $dataProvider;
    }
    
    public function update($id,$count)
    {
        if ($count==0)
        {
            if(array_key_exists($id, $this->_bag))
            {
                unset($this->_bag[$id]);
                $this->_message[$id]="<p class='text-danger'>Товар удален из корзины</p>";
            }
        }
        else
        {
            $this->_bag[$id] = $count;
            $this->_message[$id]="<p class='text-success'>Количество тваров в корзине $count</p>";
        }
        
        Yii::$app->session->set('bag',$this->_bag);
    }
    
    public function count($id=null)
    {
        if ($id!==null)
            return $this->_bag[$id];
        else
            return count($this->_bag);
    }
    
    public function message($id)
    {
        return $this->_message[$id];
    }
    
    public function formOrder()
    {
        /*
        $order = new Order();
        $order->setAttributes(['status'=>1]);
        $order->validate();
        //print_r($order->errors);die();
        //$order->setAttributes(['status'=>1]);
        //print_r($order->hasErrors());
        $order->save();
         * 
         */
        
        /*
        $order = Order::findOne(2);
        $order->status = 5;
        $order->validate();
        //print_r($order->errors);die();
        //$order->setAttributes(['status'=>1]);
        //print_r($order->hasErrors());
        $order->save();
         * 
         */
    }
}

?>
