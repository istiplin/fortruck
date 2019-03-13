<?php

namespace console\models;

use console\models\RemoteProducts;

//Класс для работы с данными о товарах полученных с локального сервера
class LocalProducts extends \yii\base\Component
{
    private $_brandsId;
    private $remote;
    
    public function __construct()
    {
        $remote = $this->remote = new RemoteProducts();
        
        $search = 'Scania';
        $products = $remote->lookupProducts($search);
         
        
        foreach ($products as $product)
        {
            if ($remote->setProducts($product['number'], $product['brandName']))
                $this->saveProducts($remote);
            else
                echo 'error'.PHP_EOL;
        }
    }
    
    private function saveProducts($remote)
    {
        //получаем список идентификаторов тех брендов, которые были получены с удаленного сервера
        $brandsId = $this->getBrandsId($remote);
        
        
        //получаем массив, елементами которого являются строки по которым идентифицируются товары
        //строки елемента массива формируются так, чтобы весь массив можно было перевести в строку,
        //которая является входным параметром для оператора in языка sql
        $inBrandNumberArray = $remote->getInBrandNumberArray($brandsId);
        
        //получаем список тех товаров с локального сервера, которые были получены с удаленного сервера
        $inBrandNumberStr = implode(',',$inBrandNumberArray);
        $sql = 'select 
                    p.brand_id, 
                    p.number, 
                    p.original_id,
                    b.name as brandName
                from product p
                left join brand b on b.id = p.brand_id
                where (brand_id,number) in('.$inBrandNumberStr.')';
        $localProducts = \Yii::$app->db->createCommand($sql)->queryAll();
        
        //обновляем информацию о существующих товарах на локального сервере
        $this->updateProducts($remote->products,$localProducts);
        
        //из списка товаров, полученные с удаленного сервера удаляем те, 
        //которые были найдены на локальном сервере
        $remote->deleteProducts($localProducts);
        
        //на локальный сервер добавляем оставшиеся товары с удаленного сервера
        $this->insertProducts($remote->products,$localProducts);
        
    }
    
    
    private function insertProducts($remoteProducts,$localProducts=[])
    {
        if (count($remoteProducts)===0)
            return;
        
        //если ни одного товара не найдено
        if (count($localProducts)==0)
        {
            $targetNumber = $this->remote->targetNumber;
            $targetBrandName = $this->remote->targetBrandName;
            
            $sql = "select id from original_product where name='$targetNumber ($targetBrandName)'";
            $originalId = \Yii::$app->db->createCommand($sql)->queryScalar();
            if ($originalId==null)
            {
                $sql = "insert into original_product(name) values('$targetNumber ($targetBrandName)')";
                \Yii::$app->db->createCommand($sql)->execute();
                $originalId = \Yii::$app->db->createCommand('SELECT LAST_INSERT_ID()')->queryScalar();
            }
        }
        else
        {
            $originalId = $localProducts[0]['original_id'];
        }
        
        $sql = "insert into product(number,name,original_id,brand_id,count,price) values ";
        
        $valuesArray=[];
        $brandsId = $this->getBrandsId();
        foreach ($remoteProducts as $brandName=>$products)
        foreach ($products as $number=>$product)
        {
            $name = ($product['name']===null)?'null':"'{$product['name']}'";
            $count = ($product['qty']===null)?'null':$product['qty'];
            $price = ($product['price']===null)?'null':$product['price'];
            $brand_id = $brandsId[$brandName];
            
            $valuesArray[] = "('$number',$name,$originalId,$brand_id,$count,$price)";
        }

        $sql.=implode(',',$valuesArray);
        \Yii::$app->db->createCommand($sql)->execute();
    }
    
    private function updateProducts($remoteProducts,$localProducts=[])
    {
        if (count($localProducts)===0)
            return;
        
        $casePriceStr = "price = case ";
        $caseCountStr = "count = case ";
        $caseNameStr = "name = case ";
        $caseUpdateTimeStr = "update_at = now() ";
        $whereInArray = [];
        foreach ($localProducts as $product)
        {
            $brandName = mb_strtoupper($product['brandName']);
            $number = $product['number'];
            $brandId = $product['brand_id'];
            
            $price = $remoteProducts[$brandName][$number]['price'];
            $price = ($price===null)?'null':$price;
            $casePriceStr.="when brand_id=$brandId AND number='$number' then $price ";
            
            $count = $remoteProducts[$brandName][$number]['qty'];
            $count = ($count===null)?'null':$count;
            $caseCountStr.="when brand_id=$brandId AND number='$number' then $count ";
            
            $name = $remoteProducts[$brandName][$number]['name'];
            $name = ($name===null)?'null':"'$name'";
            $caseNameStr.="when brand_id=$brandId AND number='$number' then $name ";
            
            $whereInArray[]="($brandId,'$number')";
        }
        
        $casePriceStr.="else price end, ";
        $caseCountStr.="else count end, ";
        $caseNameStr.="else name end, ";
        
        $whereStr = 'where (brand_id,number) in('.implode(',',$whereInArray).')';
        $sql = 'update product set '.$casePriceStr.$caseCountStr.$caseNameStr.$caseUpdateTimeStr.$whereStr;
        
        \Yii::$app->db->createCommand($sql)->execute();
    }
    
    //возвращает id брендов сгруппированные по имени
    private function getBrandsId($remote=null)
    {
        if ($remote===null)
        {
            if ($this->_brandsId!==null)
                return $this->_brandsId;
            else
                throw new \Exception('$remote is null');
        }
        
        //получаем список существующих брендов с локального сервера
        $sql = 'select id, name from brand where name in('.$remote->inBrandNamesStr.')';
        $localBrands = \Yii::$app->db->createCommand($sql)->queryAll();

        //если количество брентов с локального сервера не соответствует количеству брендов с удаленного сервера, то
        //записываем отсутствующие бренды на локальный сервер
        $insertBrandNamesArray = $remote->insertBrandNamesArray;
        if (count($localBrands) < count($insertBrandNamesArray))
        {
            //определяем какие бренды нужно записать в базу локального сервера
            foreach ($localBrands as $brand)
                unset($insertBrandNamesArray[mb_strtoupper($brand['name'])]);
        
            if(count($insertBrandNamesArray))
            {
                $sql = 'insert into brand(name) values '.implode(',',$insertBrandNamesArray);
                \Yii::$app->db->createCommand($sql)->execute();
            }
        
            //еще раз получаем список брендов с локального сервера
            $sql = 'select id, name from brand where name in('.$remote->inBrandNamesStr.')';
            $localBrands = \Yii::$app->db->createCommand($sql)->queryAll();
        }

        $brandsId = [];
        foreach ($localBrands as $brand)
            $brandsId[mb_strtoupper($brand['name'])] = $brand['id'];
        
        return $this->_brandsId = $brandsId;
    }
}