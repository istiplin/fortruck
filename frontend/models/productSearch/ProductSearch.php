<?php
namespace frontend\models\productSearch;

use Yii;
use frontend\models\bag\Bag;

abstract class ProductSearch extends \yii\base\Model
{
    //заголовок списка найденных товаров
    public $title;
    
    //ссылка на объект корзина
    protected $_bag;
    
    //возвращает данные для построения списка товаров с помощью GridView
    abstract public function getDataProvider();
    
    //возращает поля для построения списка товаров с помощью GridView
    abstract public function getColumns();
    
    //фабричный метод (определяет какой класс инициализировать)
    public static function initial($text)
    {
        //пытаемся найти товар, рассматривая поисковую строку как артикул
        $productInfo = self::_getProductInfo($text);
        
        //если товар найден
        if ($productInfo)
            //ищем его аналоги
            return new AnalogProductSearch($productInfo,Bag::initial());
        //иначе
        else
            //ищем товары, рассматривая поисковую строку как подстроку артикла или как подстроку наименования товара
            return new TextProductSearch($text,Bag::initial());
    }
    
    //находит товар, рассматривая поисковую строку как артикул
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

        $productInfo = Yii::$app->db->createCommand($query,[':text'=>$text])->queryOne();

        //если товар найден
        if ($productInfo)
        {
            //если товар не имеет наименование, даем ему наименоваие типа аналога
            if (strlen($productInfo['productName']))
                $productInfo['name'] = $productInfo['productName'];
            else
                $productInfo['name'] = $productInfo['analogName'];
        }
        
        return $productInfo;
    }
    
    //возвращает информацию о товаре, который нашли по артиклу
    public function getProductInfo()
    {
        return null;
    }
    
    public function getBag()
    {
        return $this->_bag;
    }
}
?>
