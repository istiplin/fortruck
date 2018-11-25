<?php
namespace frontend\models\productSearch;

use Yii;
use yii\data\ActiveDataProvider;
use common\models\Product;

//класс для поиска продуктов по продукту-аналогу
class AnalogProductSearch extends ProductSearch
{
    private $_productInfo;
    
    public function __construct($productInfo)
    {
        parent::init();
        $this->_productInfo = $productInfo;
        $this->title = "<h3>Аналоги для <b>{$this->_productInfo['number']}</b>:</h3>";
    }
    
    public function getProductInfo()
    {
        return $this->_productInfo;
    }
    
    public function getDataProvider()
    {
        if ($this->_dataProvider!==null)
            return $this->_dataProvider;
        
        $query = Product::find()->where(['AND',
                                            ['=','original_id',$this->_productInfo['original_id']],
                                            ['<>','id',$this->_productInfo['id']],
                                            ['=','is_visible',1]
                                        ])
                                ->orderBy('price desc');
        
        $this->_dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
        ]);
        
        return $this->_dataProvider;
    }
}
?>
