<?php

namespace frontend\models;

use yii\data\ArrayDataProvider;
use yii\data\SqlDataProvider;

//Класс для предоставления данных о товарах полученных по текстовому поиску с локального сервера
class LocalLookupProducts extends LookupProducts
{
    //возвращает информацию о товаре если поисковые данные соответствовали одному существующему товару
    public function getOneInfo()
    {
        if ($this->_oneInfo!==null)
            return $this->_oneInfo;
        
        $oneInfo = false;

        //пытаемся найти товар, рассматривая поисковую строку как артикул
        $sql = "select 
                    p.number, 
                    b.name as brandName 
                from product p
                left join brand b on b.id = p.brand_id
                where number like '{$this->_number}'";
        $products = \Yii::$app->db->createCommand($sql)->queryAll();
        if (count($products)==1)
            $oneInfo = $products[0];

        return $this->_oneInfo = $oneInfo;
    }
    
    public function getDataProvider() {
        if ($this->_dataProvider!==null)
            return $this->_dataProvider;
        

        $number = $this->_number;
        $sqlCount = "select count(*) 
                    from product p
                    left join brand b on b.id = p.brand_id
                    where (p.number like '%$number%' OR p.name like '%$number%' OR b.name like '%$number%') AND p.is_visible=1";

        $count = \Yii::$app->db->createCommand($sqlCount)->queryScalar();

        if ($count==0){
            $provider = new ArrayDataProvider;
        }
        else
        {
            $sql = 'select 
                        p.number,
                        b.name as brandName,
                        p.name
                    from product p
                    left join brand b on b.id = p.brand_id
                    where (p.number like :number OR p.name like :name OR b.name like :brandName) AND p.is_visible=1';

            $provider = new SqlDataProvider([
                'sql' => $sql,
                'params' => [
                    ':number' => '%'.$number.'%',
                    ':name' => '%'.$number.'%',
                    ':brandName' => '%'.$number.'%'
                ],
                'totalCount' => $count,
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]);

            $models = $provider->getModels();

            foreach ($models as &$model)
                $model = new Product($model);

            $provider->setModels($models);
        }
        
        return $this->_dataProvider = $provider;
    }
}
