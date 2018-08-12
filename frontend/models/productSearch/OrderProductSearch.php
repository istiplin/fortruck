<?php
namespace frontend\models\productSearch;

use Yii;
use yii\data\SqlDataProvider;
use yii\helpers\Html;

//класс для вывода заказанных продуктов
class OrderProductSearch extends ProductSearch
{
    private $id;
    
    public function __construct($id)
    {
        parent::init();
        $this->id = $id;
        $this->title = "<h3>Заказ №$id</h3>";
    }
    
    public function getDataProvider()
    {
        $sql = "select
                    p.id,
                    p.number,
                    p.name as productName,
                    a.name as analogName,
                    pr.name as producerName,
                    oi.price,
                    oi.count
                from product p
                join analog a on a.id = p.analog_id
                join producer pr on pr.id = p.producer_id
                join order_item oi on oi.product_id = p.id
                where oi.order_id=".$this->id;

        $sqlCount = "select count(*) 
                    from order_item
                    where order_id=".$this->id;
        
        $count = Yii::$app->db->createCommand($sqlCount)->queryScalar();

        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => $count,
            //'pagination' => false,
            /*
            'pagination' =>[
                'pageSize' => 2,
                'pageParam' => 'cart',
            ]
             * 
             */
        ]);
        
        return $dataProvider;
    }
  
    public function getColumns()
    {
        $cart = $this->_cart;
        return [
            'number:text:Артикул',
            [
                'label'=>'Наименование',
                'value'=>function($data){
                    if (strlen($data['productName']))
                        return $data['productName'];
                    else
                        return $data['analogName'];
                },
            ],
            'producerName:text:Производитель',
            'price:text:Цена',
            'count:text:Количество'
        ];
    }
}
?>
