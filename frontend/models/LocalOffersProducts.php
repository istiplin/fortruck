<?php

namespace frontend\models;

use yii\data\ArrayDataProvider;
use yii\data\SqlDataProvider;

//Класс для предоставления данных об аналогах полученных по искомому товару с локального сервера
class LocalOffersProducts extends OffersProducts
{
    //возвращает информацию о товаре если поисковые данные соответствовали одному существующему товару
    public function getOneInfo()
    {
        if ($this->_oneInfo!==null)
            return $this->_oneInfo;
        
        $oneInfo = false;

        $sql = "select
                    p.id,
                    p.number,
                    b.name as brandName,
                    p.name,
                    p.count,
                    p.price,
                    p.is_visible,
                    p.original_id
                from product p
                join brand b on b.id = p.brand_id
                where p.number=:number AND b.name=:brandName";

        $params = [':number'=>$this->_number, ':brandName'=>$this->_brandName];
        $models = \Yii::$app->db->createCommand($sql)->bindValues($params)->queryAll();
        if (count($models)==1)
            $oneInfo = new Product($models[0]);
        
        return $this->_oneInfo = $oneInfo;
    }
    
    public function getDataProvider() {
        if ($this->_dataProvider!==null)
            return $this->_dataProvider;
        
        $originalId = 0;
        $id = 0;
        if ($this->oneInfo AND $this->oneInfo->original_id)
            $originalId = $this->oneInfo->original_id;
        if ($this->oneInfo AND $this->oneInfo->id)
            $id = $this->oneInfo->id;

        $sqlCount = "select count(*) 
                    from product
                    where original_id={$originalId} AND id<>{$id} AND is_visible=1";

        $count = \Yii::$app->db->createCommand($sqlCount)->queryScalar();

        if ($count==0){
            $provider = new ArrayDataProvider;
        }
        else
        {
            $sql = 'select 
                        p.id,
                        p.number,
                        b.name as brandName,
                        p.name,
                        p.count,
                        p.price
                    from product p
                    left join brand b on b.id = p.brand_id
                    where p.original_id=:original_id AND p.id<>:id AND p.is_visible=1
                    order by p.count desc';

            $provider = new SqlDataProvider([
                'sql' => $sql,
                'params' => [
                    ':original_id' => $originalId,
                    ':id' => $id
                ],
                'totalCount' => $count,
                'pagination' => [
                    'pageSize' => 10,
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
