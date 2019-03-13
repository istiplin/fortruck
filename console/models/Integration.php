<?php

namespace console\models;

class Integration extends \yii\base\Component
{
    private $_apikey;
    private $_doc;
    
    public function __construct($apikey) {
        $this->_apikey = $apikey;
        $this->_doc = new \DOMDocument();
    }
    
    public function updateProduct($product)
    {
        $apikey = $this->_apikey;
        $number = $product['number'];
        $brand = $product['producer_name'];
        
        $url = "https://optipart.ru/clientapi/?apikey=$apikey&action=offers&number=$number&brand=$brand";
        //$url = "https://optipart.ru/clientapi/?apikey=63045311-e9ef-4799-84ef-da5283c96a8e&action=offers&number=SK-3140013-01&brand=S&K%20GMBH";
        //$url = "https://optipart.ru/clientapi/?apikey=63045311-e9ef-4799-84ef-da5283c96a8e&action=offers&number=SK-3140013-01&brand=SK%20GMBH";
        //$url = 'D:\web\Apache24\htdocs\www\fortrucksmsk\backend\controllers\1535250.xml';
        
        $doc = $this->_doc;
        try
        {
            $doc->load($url);
        }
        catch(\Exception $e)
        {
            return 2;
        }
        
        $typeResult = $doc->getElementsByTagName('result')->item(0)->getAttribute('type');
        if ($typeResult!='ok')
            return;
        
        $apikey = $doc->getElementsByTagName('result')->item(0)->
                        getElementsByTagName('apikey')->item(0)->nodeValue;
        if ($apikey!==$this->_apikey)
            return ;
        
        $length = $doc->getElementsByTagName('result')->item(0)->
                        getElementsByTagName('targets')->item(0)->
                        getElementsByTagName('e')->length;
        if ($length==0)
            return;

        $analogs = $doc->getElementsByTagName('result')->item(0)->
                        getElementsByTagName('targets')->item(0)->
                        getElementsByTagName('e');
        /*
        $koef = 1.3;
        $wh = [];
        foreach($analogs as $analog)
        {
            $id = $analog->getAttribute('id');
            $whi = $analog->getAttribute('whi');
            $wh[$id]['brand'] = $analog->getAttribute('bra');
            $wh[$id]['number'] = $analog->getAttribute('cod');
            $wh[$id]['name'] = $analog->getAttribute('nam');
            
            $qty = $analog->getAttribute('qty');
            if (isset($wh[$id]['qty']))
                $wh[$id]['qty'] += $qty;
            else 
                $wh[$id]['qty'] = $qty;
            
            
            $price = $analog->getAttribute('pri');
            if (isset($wh[$id]['price']))
                $wh[$id]['price'] = max($wh[$id]['price'],$price);
            else 
                $wh[$id]['price'] = $price;
            $wh[$id]['clientPrice'] = $koef*$wh[$id]['price'];
            
            
            
            $wh[$id]['wh'][$whi] = [
                                        'qty'=>$qty,
                                        'price'=>$price,
                                        'whn'=>$analog->getAttribute('whn'),
                            ];

        }
         * 
         */
        $wh = [];
        foreach($analogs as $analog)
        {
            $whi = $analog->getAttribute('whi');
            $wh['brand'] = $analog->getAttribute('bra');
            $wh['number'] = $analog->getAttribute('cod');
            $wh['name'] = $analog->getAttribute('nam');
            
            $qty = $analog->getAttribute('qty');
            if (isset($wh['qty']))
                $wh['qty'] += $qty;
            else 
                $wh['qty'] = $qty;
            
            
            $price = str_replace(',', '.', $analog->getAttribute('pri'));
            if (isset($wh['price']))
                $wh['price'] = max($wh['price'],$price);
            else 
                $wh['price'] = $price;
            $wh['clientPrice'] = $koef*$wh['price'];
            
            
            
            $wh['wh'][$whi] = [
                                        'qty'=>$qty,
                                        'price'=>$price,
                                        'whn'=>$analog->getAttribute('whn'),
                            ];

        }
        
        //echo $product['number'].PHP_EOL;

        $product->name = $wh['name'];
        $product->count = $wh['qty'];
        $product->price = $wh['price'];
        $res = $product->save();
        //if (!$res)
        //{
        //    print_r($product->getErrors());
        //    die();
        //}
        return;
    }
    
    public function updateProducts()
    {
        $products = Product::find()->all();
        $i=0;
        foreach ($products as $product)
        {
            $this->updateProduct($product);
        }
        

    }
    
    public function insertAnalogProducts($number,$brand,$originalId=null)
    {
        if ($originalId===null)
        {
            $sql = "select 
                        p.original_id 
                    from product p
                    join brand b on b.id = p.brand_id
                    where p.number='$number' and b.name='$brand'";
            $originalId = \Yii::$app->db->createCommand($sql)->queryScalar();
        }
        
        $apikey = $this->_apikey;
        
        $url = "https://optipart.ru/clientapi/?apikey=$apikey&action=offers&number=$number&brand=$brand";
        
        $doc = $this->_doc;
        try
        {
            $doc->load($url);
        }
        catch(\Exception $e)
        {
            return 2;
        }
        
        $typeResult = $doc->getElementsByTagName('result')->item(0)->getAttribute('type');
        if ($typeResult!='ok')
            return;
        
        $apikey = $doc->getElementsByTagName('result')->item(0)->
                        getElementsByTagName('apikey')->item(0)->nodeValue;
        if ($apikey!==$this->_apikey)
            return ;
        
        $length = $doc->getElementsByTagName('result')->item(0)->
                        getElementsByTagName('analogs')->item(0)->
                        getElementsByTagName('e')->length;
        if ($length==0)
            return;

        $analogs = $doc->getElementsByTagName('result')->item(0)->
                        getElementsByTagName('analogs')->item(0)->
                        getElementsByTagName('e');
        
        $groupAnalogs = [];
        foreach($analogs as $analog)
        {
            
            $price = str_replace(',', '.', $analog->getAttribute('pri'));
            
            $aBrand = strtolower($analog->getAttribute('bra'));
            $aNumber  = strtolower($analog->getAttribute('cod'));
            
            $groupAnalogs[$aBrand][$aNumber]['name'] = $analog->getAttribute('nam');
            
            $qty = $analog->getAttribute('qty');
            if (isset($groupAnalogs[$aBrand][$aNumber]['qty']))
                $groupAnalogs[$aBrand][$aNumber]['qty'] += $qty;
            else 
                $groupAnalogs[$aBrand][$aNumber]['qty'] = $qty;
            
            
            
            if (isset($groupAnalogs[$aBrand][$aNumber]['price']))
                $groupAnalogs[$aBrand][$aNumber]['price'] = max($groupAnalogs[$aBrand][$aNumber]['price'],$price);
            else 
                $groupAnalogs[$aBrand][$aNumber]['price'] = $price;
            

        }
        $values = [];
        foreach (array_keys($groupAnalogs) as $brand)
            $values["'$brand'"] = "('".$brand."')";
        
        $sql = 'select name from brand where name in('.implode(',',array_keys($values)).')';
        $res = \Yii::$app->db->createCommand($sql)->queryAll();
        foreach ($res as $rec)
        {
            $name = strtolower($rec['name']);
            unset($values["'$name'"]);
        }
            
        if (count($values))
        {
            $sql = 'insert into brand(name) values '.implode(',',$values);
            \Yii::$app->db->createCommand($sql)->execute();
        }
        
        $values = [];
        foreach (array_keys($groupAnalogs) as $brand)
            $values[] = "'$brand'";
        
        $sql = 'select id, name from brand where name in('.implode(',',$values).')';
        $res = \Yii::$app->db->createCommand($sql)->queryAll();
        $brandIdList = [];
        foreach ($res as $rec)
        {
            $name = strtolower($rec['name']);
            $brandIdList[$name] = $rec['id'];
        }
        
        $values = [];
        //print_r($groupAnalogs);
        foreach ($groupAnalogs as $brand=>$analogs)
            foreach ($analogs as $number=>$analog)
            {
                
                $record = [
                            "'$number'",
                            $brandIdList[$brand],
                            $originalId,
                            "'{$analog['name']}'",
                            $analog['qty'],
                            $analog['price']
                ];
                $values[] = "(".implode(',',$record).")";
            }
            
        $sql = 'insert ignore into product(number, brand_id,original_id,name,count,price) 
                values '.implode(',',$values);
        \Yii::$app->db->createCommand($sql)->execute();
    }
    
    public function insertProducts()
    {
        $this->insertAnalogProducts('MTX001-0005','MTX');
        die();
        
        $sql = 'select 
                    b.name as brandName,
                    p.number,
                    p.original_id
                from product p
                left join brand b on b.id=p.brand_id';
        $res = \Yii::$app->db->createCommand($sql)->queryAll();
        
        $originalIdList = [];
        foreach ($res as $rec)
            $originalIdList[$rec['brandName']][$rec['number']] = $rec['original_id'];
        
        //ob_start();
        //print_r($originalIdList['ТСП']);
        //echo iconv('UTF-8', 'cp866', ob_get_clean());
        //echo count($originalIdList);
        
        foreach ($originalIdList as $brandName=>$brandOriginalIdList)
            foreach ($brandOriginalIdList as $number=>$originalId)
            {
                echo $brandName.' '.$number.' '.$originalId.PHP_EOL;
                $this->insertAnalogProducts($number,$brandName,$originalId);
                die();
            }
    }
}
?>
