<?php
namespace frontend\models;

use Yii;
//use backend\models\ProductSearch;
use common\models\Product;
use yii\data\ActiveDataProvider;

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
        $this->_productInfo = Yii::$app->db->createCommand($query,[':search'=>$this->search])
                //->bindValue(':search',"'{$this->search}'")
                ->queryOne();
        //print_r($this->_productInfo);
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
        $query = Product::find()->joinWith(['analog','producer']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            //'pagination' =>[
            //    'pageSize' => 2,
            //]
        ]);
        
        $dataProvider->setSort([
            'attributes' => [
                    'number'=>[
                        'asc'=>['number'=>SORT_ASC],
                        'desc'=>['number'=>SORT_DESC],
                    ],
                    'producerName'=>[
                        'asc'=>['producer.name'=>SORT_ASC],
                        'desc'=>['producer.name'=>SORT_DESC],
                    ]
                ]
        ]);
        
        // grid filtering conditions
        $query->where(['analog_id' => $this->productInfo['analogId']]);
        $query->andWhere(['<>','product.id',$this->productInfo['id']]);
        
        return $dataProvider;
    }
    
    public function getDataProviderForProducts()
    {
        $query = Product::find()
                //->select('product.*,analog.*,producer.*')
                ->joinWith(['analog','producer']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            //'pagination' =>[
            //    'pageSize' => 2,
            //]
        ]);
        
        $dataProvider->setSort([
            'attributes' => [
                    'number'=>[
                        'asc'=>['number'=>SORT_ASC],
                        'desc'=>['number'=>SORT_DESC],
                    ],
                    'producerName'=>[
                        'asc'=>['producer.name'=>SORT_ASC],
                        'desc'=>['producer.name'=>SORT_DESC],
                    ]
                ]
        ]);
        
        // grid filtering conditions
        $query->where(['like','number',$this->search]);
        
        return $dataProvider;
    }
}
?>
