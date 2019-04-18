<?php

namespace frontend\models;

use yii\data\ArrayDataProvider;

//Класс для предоставления данных об аналогах полученных по искомому товару с удаленного сервера
class RemoteOffersProducts extends OffersProducts
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

        if (count($this->data['targets']))
            $oneInfo = new Product($this->data['targets'][0]);
        
        return $this->_oneInfo = $oneInfo;
    }
    
    public function getDataProvider()
    {
        if ($this->_dataProvider!==null)
            return $this->_dataProvider;
        

        if (count($this->data['analogs'])==0){
            $provider = new ArrayDataProvider;
        }
        else{
            $provider = new ArrayDataProvider([
                'allModels' => $this->data['analogs'],
                'pagination' => [
                    'pageSize' => 100,
                ],
            ]);

            $models = $provider->getModels();

            foreach ($models as &$model)
                $model = new Product($model);

            $provider->setModels($models);
        }
        
        return $this->_dataProvider = $provider;
    }
    
    public function getData()
    {
        if ($this->_data!==null)
            return $this->_data;
        
        $data['targets']=[];
        $data['analogs']=[];
        
        $number = rawurlencode($this->_number);
        $brandName = rawurlencode($this->_brandName);
        
        $url = "https://optipart.ru/clientapi/?apikey={$this->apikey}&action=offers&number=$number&brand=$brandName";
        //$url = "D:/web/Apache24/htdocs/www/fortrucksmsk/xml_test/optipart4.ru.xml";

        $xml = $this->loadXml($url);
        
        $groupDatas['targets']=[];
        $groupDatas['analogs']=[];
        
        $inBrandNumberArray = [];
        
        foreach (array_keys($groupDatas) as $type)
        {
            if ($xml->getElementsByTagName($type))
                $count = $xml->getElementsByTagName($type)->item(0)->getElementsByTagName('e')->length;
            
            if ($count)
            {
                $products = $xml->getElementsByTagName($type)->item(0)->getElementsByTagName('e');
                foreach ($products as $product)
                {
                    $price = str_replace(',', '.', $product->getAttribute('pri'));

                    $brandName = mb_strtoupper($product->getAttribute('bra'));
                    $number  = mb_strtoupper($product->getAttribute('cod'));

                    $groupDatas[$type][$brandName][$number]['brandName'] = $brandName;
                    $groupDatas[$type][$brandName][$number]['number'] = $number;
                    
                    $groupDatas[$type][$brandName][$number]['name'] = $product->getAttribute('nam');

                    $qty = $product->getAttribute('qty');
                    if (isset($groupDatas[$type][$brandName][$number]['count']))
                        $groupDatas[$type][$brandName][$number]['count'] += $qty;
                    else 
                        $groupDatas[$type][$brandName][$number]['count'] = (int)$qty;

                    if (isset($groupDatas[$type][$brandName][$number]['price']))
                        $groupDatas[$type][$brandName][$number]['price'] = max($groupDatas[$type][$brandName][$number]['price'],$price);
                    else 
                        $groupDatas[$type][$brandName][$number]['price'] = $price;
                    
                    $inBrandNumberArray[$brandName.'_'.$number] = "('$brandName','$number')";
                }
            }
        }
        
        Product::setListId(implode(',',$inBrandNumberArray));

        foreach ($groupDatas as $type=>$groupData)
        foreach($groupData as $brandName=>$products)
        foreach($products as $number=>$product)
            $data[$type][] = $product;
        
        return $this->_data = $data;
    }
}