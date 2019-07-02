<?php

namespace console\models;

use console\models\RemoteProducts;
use common\components\Helper;

//Класс для работы с данными о товарах полученных с локального сервера
class LocalProducts extends \yii\base\Component
{
    private $_products;
    private $_brandsId;
    
    private $remote;
    
    
    
    /*
    public function __construct()
    {
        $remote = $this->remote = new RemoteProducts();
        
        $search = 'Scania';
        $products = $remote->getLookup($search);
         
        
        foreach ($products as $product)
        {
            if ($remote->setProducts($product['number'], $product['brandName']))
            {
                $this->saveProducts($remote);
                echo 'ok'.PHP_EOL;
            }
            else
                echo 'error'.PHP_EOL;
        }
    }
     * 
     */
    
    //function setOffers($number, $brandName)
    //{
    //  ...
    //  $this->_brandsId = null;
    //}
    //function getLocalProducts($remote)
    //{
    // 
    //}
    
    public function updateAll()
    {
        $this->remote = new RemoteProducts();
        $oldDate = date("Y-m-d H:i:s",time()-60);
        //do{
            $sql = "select
                        p.norm_number,
                        b.name as brandName
                    from product p
                    join brand b on b.id = p.brand_id
                    where update_at<'$oldDate'
                    order by update_at";
            
            $idList = \Yii::$app->db->createCommand($sql)->queryAll();
            
            $groupIdList = [];
            foreach ($idList as $id)
            {
                $normNumber = Helper::normNumber($id['norm_number']);
                $groupIdList[mb_strtoupper($id['brandName'])][$normNumber]=true;
            }
            //print_r($groupIdList);
            //return;
            foreach($groupIdList as $brandName=>&$normNumbers)
                foreach($normNumbers as $normNumber=>$isActive)
                {
                    echo $brandName.' '.$normNumber.' '.$isActive.PHP_EOL;
                
                    if (!$isActive)
                    {
                        echo 'no active'.PHP_EOL;
                        continue;
                    }
                    
                    if ($this->setRemoteProducts($normNumber, $brandName))
                    {
                        $this->update();
                        //$this->remote->delete($this->products);
                        //$this->insert();
                    }
                    //else
                    //    echo 'error'.PHP_EOL;
                    
                    foreach($this->remote->products as $brandName2=>$normNumbers2)
                        foreach (array_keys($normNumbers2) as $normNumber2)
                        {
                            if (isset($groupIdList[$brandName2][$normNumber2]))
                            {
                                //echo 'no active='.PHP_EOL;
                                $groupIdList[$brandName2][$normNumber2] = false;
                            }
                        }
                }
        //}while (count($res));
        //print_r($groupIdList);
    }
    
    private function setRemoteProducts($normNumber, $brandName)
    {
        $this->_products = null;
        $this->_brandsId = null;
        return $this->remote->setProducts($normNumber, $brandName);
    }
    
    private function getProducts()
    {
        if ($this->_products!==null)
            return $this->_products;
        
        //получаем список идентификаторов тех брендов, которые были получены с удаленного сервера
        $brandsId = $this->getBrandsId();

        //получаем массив, елементами которого являются строки по которым идентифицируются товары
        //строки елемента массива формируются так, чтобы весь массив можно было перевести в строку,
        //которая является входным параметром для оператора in языка sql
        $inBrandNumberArray = $this->remote->getInBrandNumberArray($brandsId);
        
        //получаем список тех товаров с локального сервера, которые были получены с удаленного сервера
        $inBrandNumberStr = implode(',',$inBrandNumberArray);
        $sql = 'select 
                    p.brand_id, 
                    p.norm_number, 
                    p.original_id,
                    b.name as brandName
                from product p
                left join brand b on b.id = p.brand_id
                where (brand_id,norm_number) in('.$inBrandNumberStr.')';
        
        return $this->_products = \Yii::$app->db->createCommand($sql)->queryAll();
    }
    /*
    private function insert()
    {
        $localProducts = $this->getProducts();
        if (count($localProducts)===0)
            return;
        
        $remoteProducts = $this->remote->products;
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
     * 
     */
    
    private function update()
    {
        $localProducts = $this->getProducts();
        if (count($localProducts)===0)
            return;
        
        $remoteProducts = $this->remote->products;
        
        $casePriceStr = "price = case ";
        $caseCountStr = "count = case ";
        $caseNameStr = "name = case ";
        $caseUpdateTimeStr = "update_at = now() ";
        $whereInArray = [];
        foreach ($localProducts as $product)
        {
            $brandName = mb_strtoupper($product['brandName']);
            $normNumber = $product['norm_number'];
            $brandId = $product['brand_id'];
            
            $price = $remoteProducts[$brandName][$normNumber]['price'];
            $price = ($price===null)?'null':$price;
            $casePriceStr.="when brand_id=$brandId AND norm_number='$normNumber' then $price ";
            
            $count = $remoteProducts[$brandName][$normNumber]['qty'];
            $count = ($count===null)?'null':$count;
            $caseCountStr.="when brand_id=$brandId AND norm_number='$normNumber' then $count ";
            
            $name = $remoteProducts[$brandName][$normNumber]['name'];
            $name = ($name===null)?'null':"'$name'";
            $caseNameStr.="when brand_id=$brandId AND norm_number='$normNumber' then $name ";
            
            $whereInArray[]="($brandId,'$normNumber')";
        }
        
        $casePriceStr.="else price end, ";
        $caseCountStr.="else count end, ";
        $caseNameStr.="else name end, ";
        
        $whereStr = 'where (brand_id,norm_number) in('.implode(',',$whereInArray).')';
        $sql = 'update product set '.$casePriceStr.$caseCountStr.$caseNameStr.$caseUpdateTimeStr.$whereStr;
        
        \Yii::$app->db->createCommand($sql)->execute();
    }
    
    //возвращает id брендов сгруппированные по имени
    private function getBrandsId()
    {
        if ($this->_brandsId!==null)
            return $this->_brandsId;
        
        //получаем список существующих брендов с локального сервера
        $sql = 'select id, name from brand where name in('.$this->remote->inBrandNamesStr.')';
        $localBrands = \Yii::$app->db->createCommand($sql)->queryAll();

        //если количество брентов с локального сервера не соответствует количеству брендов с удаленного сервера, то
        //записываем отсутствующие бренды на локальный сервер
        $insertBrandNamesArray = $this->remote->insertBrandNamesArray;
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
            $sql = 'select id, name from brand where name in('.$this->remote->inBrandNamesStr.')';
            $localBrands = \Yii::$app->db->createCommand($sql)->queryAll();
        }

        $brandsId = [];
        foreach ($localBrands as $brand)
            $brandsId[mb_strtoupper($brand['name'])] = $brand['id'];
        
        return $this->_brandsId = $brandsId;
    }
}