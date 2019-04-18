<?php

namespace frontend\models;
//класс для предоставления данных о товарах
abstract class Products extends \yii\base\Component
{
    protected $_number;
    
    protected $_oneInfo;
    protected $_dataProvider;
    
    protected $_title;
    
    //фабричный метод, который определяет что искать, товары по текстовому поиску или аналоги по искомоу товару
    public static function initial($number,$brandName=null,$isRemote=true)
    {
        $number = trim($number);
        
        //$number = preg_replace("/[^а-яёa-z0-9]/iu", '', $number);
        
        if ($brandName!==null AND strlen($number)>0)
            return OffersProducts::initialProducts($number,$brandName,$isRemote);
 
        $search = LookupProducts::initialProducts($number, $isRemote);
        if ($product = $search->getOneInfo())
            return OffersProducts::initialProducts($product['number'],$product['brandName'],$isRemote);

        return $search;
    }
    
    abstract public function getTitle();

    //возвращает информацию о товаре если поисковые данные соответствовали этому товару
    abstract public function getOneInfo();
    
    //возвращает данные о товаре
    abstract public function getDataProvider();
    
    //возвращает список полей, где будет отображаться информация о товарах
    abstract public function getColumns();
    
    abstract public function getRowOptions();
    
    public function getAttributeLabel($attribute)
    {
        return $this->_dataProvider->models[0]->getAttributeLabel($attribute);
    }
}
