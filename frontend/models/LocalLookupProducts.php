<?php

namespace frontend\models;

use yii\data\ArrayDataProvider;
use yii\data\SqlDataProvider;
use common\components\Helper;

//Класс для предоставления данных о товарах полученных по текстовому поиску с локального сервера
class LocalLookupProducts extends LookupProducts
{
    //возвращает информацию о товаре если поисковые данные соответствовали одному существующему товару
    public function getOneInfo()
    {
        if ($this->_oneInfo!==null)
            return $this->_oneInfo;
        
        $oneInfo = false;

        $normNumber = Helper::normNumber($this->_number);
        
        //пытаемся найти товар, рассматривая поисковую строку как артикул
        $sql = "select 
                    p.number,
                    p.norm_number,
                    b.name as brandName 
                from product p
                left join brand b on b.id = p.brand_id
                where norm_number like '$normNumber'";
        $products = \Yii::$app->db->createCommand($sql)->queryAll();
        if (count($products)==1)
            $oneInfo = $products[0];

        return $this->_oneInfo = $oneInfo;
    }
    
    public function getDataProvider() {
        if ($this->_dataProvider!==null)
            return $this->_dataProvider;
        
        $normNumber = Helper::normNumber($this->_number);
        $number = $this->_number;
        $params = [
                    ':norm_number' => '%'.$normNumber.'%',
                    ':number' => '%'.$number.'%'
                ];
        $sqlCount = "select count(*) 
                    from product p
                    left join brand b on b.id = p.brand_id
                    where (p.norm_number like :norm_number OR p.name like :number OR b.name like :number) AND p.is_visible=1";

        $count = \Yii::$app->db->createCommand($sqlCount,$params)->queryScalar();

        if ($count==0){
            $provider = new ArrayDataProvider;
        }
        else
        {
            $sql = 'select 
                        p.number,
                        p.norm_number,
                        b.name as brandName,
                        p.name
                    from product p
                    left join brand b on b.id = p.brand_id
                    where (p.norm_number like :norm_number OR p.name like :number OR b.name like :number) AND p.is_visible=1';

            $provider = new SqlDataProvider([
                'sql' => $sql,
                'params' => $params,
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
