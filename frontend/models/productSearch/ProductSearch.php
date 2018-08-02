<?php
namespace frontend\models\productSearch;

use Yii;
use frontend\models\bag\Bag;

abstract class ProductSearch extends \yii\base\Model
{
    public $title;
    protected $_bag;
    
    abstract public function getDataProvider();
    abstract public function getColumns();
    
    //фабричный метод (определяет какой класс инициализировать)
    public static function initial($text)
    {
        $productInfo = self::_getProductInfo($text);
        
        if ($productInfo)
            return new AnalogProductSearch($productInfo,Bag::initial());
        else
            return new TextProductSearch($text,Bag::initial());
    }
    
    private static function _getProductInfo($text)
    {
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
                where number=:text";
        //echo $query;
        $productInfo = Yii::$app->db->createCommand($query,[':text'=>$text])->queryOne();

        if ($productInfo)
        {
            if (strlen($productInfo['productName']))
                $productInfo['name'] = $productInfo['productName'];
            else
                $productInfo['name'] = $productInfo['analogName'];
        }
        
        return $productInfo;
    }
    
    public function getProductInfo()
    {
        return false;
    }
    
    //обновляет корзину
    public function updateBag($id,$count)
    {
        $this->_bag->update($id,$count);
    }
}
?>
