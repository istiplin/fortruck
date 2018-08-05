<?php
namespace frontend\models\bag;

use Yii;
use common\models\Bag as ARBag;

//класс корзина для авторизованных пользователей
class AuthBag extends Bag
{
    public function update($id,$count)
    {
        $bag = ARBag::findOne(['user_id'=>Yii::$app->user->identity->id,'product_id'=>$id]);
        if ($bag)
        {
            if ($count==0)
            {
                $bag->delete();
                $this->_message = [$id=>"<p class='text-danger'>Товар удален из корзины</p>"];
            }
            else
            {
                $bag->count=$count;
                $bag->save();
                $this->_message = [$id=>"<p class='text-success'>Количество тваров в корзине $count</p>"];
            }
        }
        elseif ($count!=0) 
        {
            $bag = new ARBag();
            $bag->user_id = Yii::$app->user->identity->id;
            $bag->product_id = $id;
            $bag->count = $count;
            $bag->save();
            $this->_message = [$id=>"<p class='text-success'>Количество тваров в корзине $count</p>"];
        }
    }
    
    //определяет количество типов товаров в корзине
    public function getTypeCount()
    {
        if ($this->_typeCount!==null)
            return $this->_typeCount;
        
        return $this->_typeCount = ARBag::find(['user_id'=>Yii::$app->user->identity->id])->count();
    }
    
    public function clear()
    {
        ARBag::deleteAll(['user_id'=>Yii::$app->user->identity->id]);
    }
   
    //возвращает информацию о товарах в корзине
    public function getProductsInfo()
    {
        $sql = "select
                    p.id,
                    p.price,
                    b.count
                from product p
                join bag b on b.product_id = p.id
                where b.user_id=".Yii::$app->user->identity->id;
        
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        
        $productsInfo = [];
        foreach($res as $rec)
            $productsInfo[$rec['id']] = $rec;
        
        return $productsInfo;
    }
}
