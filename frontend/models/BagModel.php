<?php

namespace frontend\models;

use yii;
use yii\data\ActiveDataProvider;

use common\models\Product;

class BagModel extends \yii\base\Model
{
    private $_bag;
    private $_message=[];
    
    public function  __construct($bag=[])
    {
        $this->_bag = $bag;
    }

    public function getDataProvider()
    {
        $query = Product::find()->joinWith(['analog','producer']);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            //'pagination' =>[
            //    'pageSize' => 2,
            //]
        ]);
        
        $query->where('product.id in('.implode(',',array_keys($this->_bag)).')');

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

    public function get()
    {
        return $this->_bag;
    }
}

?>
