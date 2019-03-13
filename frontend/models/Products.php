<?php

namespace frontend\models;
//класс для предоставления данных о товарах
abstract class Products extends \yii\base\Component
{
    protected $_number;
    
    protected $_oneInfo;
    protected $_dataProvider;
    
    public $title;
    
    //фабричный метод, который определяет что искать, товары по текстовому поиску или аналоги по искомоу товару
    public static function initial($number,$brandName=null,$isRemote=true)
    {
        $number = trim($number);
        if (strlen($number)==0)
            return false;
        
        if ($brandName!==null)
            return OffersProducts::initialProducts($number,$brandName,$isRemote);
 
        $search = LookupProducts::initialProducts($number, $isRemote);
        if ($product = $search->getOneInfo())
            return OffersProducts::initialProducts($product['number'],$product['brandName'],$isRemote);

        return $search;
    }
    
    //возвращает информацию о товаре если поисковые данные соответствовали этому товару
    abstract public function getOneInfo();
    
    //возвращает данные о товаре
    abstract public function getDataProvider();
    
    //возвращает список полей, где будет отображаться информация о товарах
    abstract public function getColumns();
}
