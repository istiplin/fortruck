<?php
namespace frontend\models;

use Yii;
use common\models\Product;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\db\Query;

class SearchModel extends \yii\base\Model
{
    public $search;
    private $_productInfo;
    
    public function __construct($search)
    {
        $this->search = $search;
    }
    
    public function getProductInfo()
    {
        if ($this->_productInfo!==null)
            return $this->_productInfo;
        
        $query = "select 
                    p.id,
                    p.number,
                    p.price,
                    p.name as productName,
                    a.id as analogId,
                    a.name as analogName,
                    pr.name as producerName
                from product p
                left join analog a on a.id = p.analog_id
                left join producer pr on pr.id = p.producer_id
                where number=:search";
        //echo $query;
        $this->_productInfo = Yii::$app->db->createCommand($query,[':search'=>$this->search])->queryOne();

        if ($this->_productInfo)
        {
            if (strlen($this->_productInfo['productName']))
                $this->_productInfo['name'] = $this->_productInfo['productName'];
            else
                $this->_productInfo['name'] = $this->_productInfo['analogName'];
        }
        return $this->_productInfo;
    }
    
    public function getDataProviderForAnalog()
    {
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
                where p.analog_id=:analog_id and p.id<>:id";

        $count = Yii::$app->db->createCommand("select 
                                                    count(*)
                                                from product
                                                where analog_id=:analog_id and id<>:id",
                    [':analog_id'=>$this->productInfo['analogId'],':id'=>$this->productInfo['id']])->queryScalar();
                
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'params' => [':analog_id'=>$this->productInfo['analogId'],':id'=>$this->productInfo['id']],
            'totalCount' => $count,
            //'pagination' =>[
            //    'pageSize' => 2,
            //]
        ]);
        
        return $dataProvider;
    }
    
    public function getDataProviderForProducts()
    {
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
                where p.number like :search";

        $count = Yii::$app->db->createCommand("select 
                                                    count(*)
                                                from product
                                                where number like :search",
                    [':search'=>"%{$this->search}%"])->queryScalar();
                
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'params' => [':search'=>"%{$this->search}%"],
            'totalCount' => $count,
            //'pagination' =>[
            //    'pageSize' => 2,
            //]
        ]);
        
        return $dataProvider;
    }
}
?>
