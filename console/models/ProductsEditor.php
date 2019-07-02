<?php
namespace console\models;

use common\components\Helper;

class ProductsEditor {
    
    public function updateNormNumbers()
    {
        $sql = 'select count(*) from product';
        $count = \Yii::$app->db->createCommand($sql)->queryScalar();
        $recordCount = 500;
        for($offset=0; $offset<$count; $offset+=$recordCount)
        {
            $sql = "select
                        id,
                        number
                    from product
                    order by id
                    limit $offset,$recordCount";
            $res = \Yii::$app->db->createCommand($sql)->queryAll();
            
            if (count($res))
            {
                $normRes = [];
                foreach ($res as $rec)
                    $normRes[$rec['id']] = Helper::normNumber($rec['number']);

                $sqlCaseStr = '';
                
                foreach ($normRes as $key=>$norNumber)
                    $sqlCaseStr.="when id=$key then '$norNumber' ";

                $sqlWhereStr = 'where id in('.implode(',',array_keys($normRes)).')';
                $sql = 'update product set norm_number = case '.$sqlCaseStr.'end '.$sqlWhereStr;
                \Yii::$app->db->createCommand($sql)->execute();
            }
        }
    }
    
    public function deleteDublicateNumbers()
    {
        $sql = 'select 
                    id
                from product p1 
                where exists(
                    SELECT 1
                    from product p2
                    where p1.norm_number = p2.norm_number and p1.brand_id = p2.brand_id
                    group by p2.norm_number, p2.brand_id
                    having count(*)>1
                )
                and p1.number = p1.norm_number
                order by norm_number';
        $res = \Yii::$app->db->createCommand($sql)->queryColumn();
        
        $sql = 'delete from product where id in('.implode(',',$res).')';
        \Yii::$app->db->createCommand($sql)->execute();
    }
}
?>
