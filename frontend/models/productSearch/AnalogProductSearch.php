<?php
namespace frontend\models\productSearch;

use Yii;
use yii\data\SqlDataProvider;

//класс для поиска продуктов по продукту-аналогу
class AnalogProductSearch extends ProductSearch
{
    private $_productInfo;
    
    public function __construct($productInfo)
    {
        parent::init();
        $this->_productInfo = $productInfo;
        $this->title = "<h3>Аналоги для <b>{$this->_productInfo['name']}</b>:</h3>";
    }
    
    public function getProductInfo()
    {
        return $this->_productInfo;
    }
    
    public function getDataProvider()
    {
        $sql = "select *
                from product
                where price>0 and original_id={$this->_productInfo['original_id']} and id<>{$this->_productInfo['id']}";

        $sqlCount = "select 
                        count(*)
                    from product
                    where price>0 and original_id={$this->_productInfo['original_id']} and id<>{$this->_productInfo['id']}";
                
        $count = Yii::$app->db->createCommand($sqlCount)->queryScalar();
                
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => $count,
            //'pagination' =>[
            //    'pageSize' => 2,
            //]
        ]);
        
        return $dataProvider;
    }
}
?>
