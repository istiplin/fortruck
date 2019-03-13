<?php

namespace console\models;

//Класс для работы с данными о товарах полученных с удаленного сервера
class RemoteProducts extends \common\models\RemoteProducts
{
    public $targetNumber;
    public $targetBrandName;
    
    private $_products;
    
    public $inBrandNamesArray=[];
    public $insertBrandNamesArray=[];
    public $inBrandNamesStr='';
    public $insertBrandNamesStr='';
    
    //public $isFoundTarget = true;
    
    public function lookupProducts($search)
    {
        $search = rawurlencode($search);
        $url = "https://optipart.ru/clientapi/?apikey={$this->apikey}&action=lookup&number=$search";
        $xml = $this->loadXml($url);
        
        $productsXml = $xml->getElementsByTagName('a')->item(0)->getElementsByTagName('e');
        $products = [];
        foreach ($productsXml as $productXml)
        {
            $products[]=[
                'brandName'=>$productXml->getAttribute('bnd'),
                'number'=>$productXml->getAttribute('numnorm')
            ];
        }
        
        return $products;
    }
    
    private function refreshProductsInfo()
    {
        foreach (array_keys($this->_products) as $brandName)
        {
            $this->inBrandNamesArray[$brandName] = "'".$brandName."'";
            $this->insertBrandNamesArray[$brandName] = "('".$brandName."')";
        }
        $this->inBrandNamesArray[$this->targetBrandName] = "'".$this->targetBrandName."'";
        $this->insertBrandNamesArray[$this->targetBrandName] = "('".$this->targetBrandName."')";
        
        $this->inBrandNamesStr = implode(',',$this->inBrandNamesArray);
        $this->insertBrandNamesStr = implode(',',$this->insertBrandNamesArray);
    }
    
    public function getInBrandNumberArray($brandsId)
    {
        $inBrandNumberArray = [];
        $products = $this->_products;
        foreach ($products as $brandName=>$products)
        foreach (array_keys($products) as $number)
        {
            $brandId = $brandsId[$brandName];
            $inBrandNumberArray[$brandId.'_'.mb_strtoupper($number)] = "($brandId,'$number')";
        }
        return $inBrandNumberArray;
    }
    
    //удаляет товары, содержащиеся в $products
    public function deleteProducts($products=[])
    {
        if (count($products)===0)
            return;
        
        foreach ($products as $product)
        {
            $brandName = mb_strtoupper($product['brandName']);
            unset($this->_products[$brandName][$product['number']]);
            if (count($this->_products[$brandName])===0)
                unset($this->_products[$brandName]);
        }
        $this->refreshProductsInfo();
    }
    
    public function getProducts()
    {
        return $this->_products;
    }
    
    public function setProducts($number,$brandName)
    {
        $this->targetNumber = mb_strtoupper($number);
        $this->targetBrandName = mb_strtoupper($brandName);
        
        $targetBrandName = rawurlencode($this->targetBrandName);
        
        $this->_products = [];
        
        $url = "https://optipart.ru/clientapi/?apikey={$this->apikey}&action=offers&number={$this->targetNumber}&brand=$targetBrandName";
        $xml = $this->loadXML($url);

        if ($xml===false)
            return false;
        
        $this->_products = $this->addProducts($this->_products,$this->getProductsXML($xml,'targets'));
        $this->_products = $this->addProducts($this->_products,$this->getProductsXML($xml,'analogs'));
        
        if (count($this->_products)===0)
            return false;
        
        $this->addTargetProduct();
        
        $this->refreshProductsInfo();
        return true;
    }
    
    private function addTargetProduct()
    {
        $targetBrandName = $this->targetBrandName;
        $targetNumber = $this->targetNumber;
        if (!isset($this->_products[$targetBrandName]))
        {
            $this->_products[$targetBrandName] = [
                $targetNumber => [
                    'name'=>null,
                    'qty'=>null,
                    'price'=>null
                ]
            ];
        }
        elseif (!isset($this->_products[$targetBrandName][$targetNumber]))
        {
            $this->_products[$targetBrandName][$targetNumber] = [
                'name'=>null,
                'qty'=>null,
                'price'=>null
            ];
        }
    }
    
    private function getProductsXML(&$xml,$type)
    {
        $length = $xml->getElementsByTagName($type)->item(0)->getElementsByTagName('e')->length;
        if ($length==0)
            return [];
        
        return $xml->getElementsByTagName($type)->item(0)->getElementsByTagName('e');
    }
    
    private function addProducts(&$products,&$productsXML)
    {
        foreach($productsXML as $product)
        {
            $price = str_replace(',', '.', $product->getAttribute('pri'));
            
            $brandName = mb_strtoupper($product->getAttribute('bra'));
            $number  = mb_strtoupper($product->getAttribute('cod'));
            
            $products[$brandName][$number]['name'] = $product->getAttribute('nam');
            
            $qty = $product->getAttribute('qty');
            if (isset($products[$brandName][$number]['qty']))
                $products[$brandName][$number]['qty'] += $qty;
            else 
                $products[$brandName][$number]['qty'] = (int)$qty;
            
            
            
            if (isset($products[$brandName][$number]['price']))
                $products[$brandName][$number]['price'] = max($products[$brandName][$number]['price'],$price);
            else 
                $products[$brandName][$number]['price'] = $price;
        }
        return $products;
    }
}
?>