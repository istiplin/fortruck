<?php

namespace frontend\models;

use yii\data\ArrayDataProvider;


//Класс для предоставления данных о товарах полученных по текстовому поиску с удаленного сервера
class RemoteLookupProducts extends LookupProducts
{
    private $_data;
    
    public function behaviors() {
        return['common\models\RemoteProducts'];
    }
    
    //возвращает информацию о товаре если поисковые данные соответствовали одному существующему товару
    public function getOneInfo()
    {
        if ($this->_oneInfo!==null)
            return $this->_oneInfo;
        
        $oneInfo = false;
        if (count($this->data)==1)
            $oneInfo = $this->data[0];
        
        return $this->_oneInfo = $oneInfo;
    }
    
    public function getDataProvider()
    {
        if ($this->_dataProvider!==null)
            return $this->_dataProvider;
        
        $provider = new ArrayDataProvider([
            'allModels' => $this->data,
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);

        $models = $provider->getModels();

        foreach ($models as &$model)
            $model = new Product($model);

        $provider->setModels($models);

        
        return $this->_dataProvider = $provider;
    }
    
    public function getData()
    {
        if ($this->_data!==null)
            return $this->_data;

        $data = [];
        
        $number = rawurlencode($this->_number);
        $url = "https://optipart.ru/clientapi/?apikey={$this->apikey}&action=lookup&number=$number";
        //$url = "D:/web/Apache24/htdocs/www/fortrucksmsk/xml_test/optipart.ru(lookupScania).xml";
        $xml = $this->loadXml($url);
        $elems = $xml->getElementsByTagName('a')->item(0)->getElementsByTagName('e');
        foreach ($elems as $elem)
        {
            $data[]=[
                'brandName'=>$elem->getAttribute('bnd'),
                'number'=>$elem->getAttribute('numnorm'),
                'name'=>$elem->getAttribute('nam'),
            ];
        }

        return $this->_data = $data;
    }
}