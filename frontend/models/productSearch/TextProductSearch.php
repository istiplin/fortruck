<?php
namespace frontend\models\productSearch;

use Yii;
use yii\data\SqlDataProvider;

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
        $sql="select *
                from product
                where price>0 and (number like :text or name like :text)";
                    
        $sqlCount = "select 
                        count(*)
                    from product
                    where price>0 and (number like :text or name like :text)";
        
        $count = Yii::$app->db->createCommand($sqlCount,[':text'=>"%{$this->_text}%"])->queryScalar();
                
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'params' => [':text'=>"%{$this->_text}%"],
            'totalCount' => $count,
            //'pagination' =>[
            //    'pageSize' => 2,
            //]
        ]);
        //print_r($dataProvider); die();
        return $dataProvider;
    }
    
}
?>
