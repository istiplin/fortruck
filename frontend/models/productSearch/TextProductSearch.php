<?php
namespace frontend\models\productSearch;

use Yii;
use yii\data\ActiveDataProvider;
use common\models\Product;

//класс для поиска продуктов по тексту
class TextProductSearch extends ProductSearch
{
    private $_text;
    
    public function __construct($text)
    {
        parent::init();
        $this->_text = $text;
        $this->title='';
        if (strlen($this->_text))
            $this->title = "<h4>Результаты поиска по запросу <b>'{$this->_text}'</b></h4>";
    }
    
    public function getDataProvider()
    {
        if ($this->_dataProvider!==null)
            return $this->_dataProvider;
        
        $query = Product::find()->where([   'AND',
                                            ['OR',['like','number',$this->_text],['like','name',$this->_text]],
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
