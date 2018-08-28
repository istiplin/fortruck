<?php
namespace frontend\models\productSearch;

use Yii;
use yii\data\SqlDataProvider;
use yii\helpers\Html;
use common\models\OrderItem;

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
        $sql = "select p.*,
                    oi.price,
                    oi.count
                from product p
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
        $cart = $this->cart;
        return [
            'number:text:Артикул',
            'name:text:Наименование',
            'producer_name:text:Производитель',
            'price:text:Цена',
            'count:text:Количество'
        ];
    }
    
    public function getPriceSum()
    {
        $priceSum = OrderItem::find()
            ->where(['order_id'=>$this->id])
            ->sum('price*count');
        return (($priceSum)?sprintf("%01.2f", $priceSum):sprintf("%01.2f", 0)).' руб.';
    }
}
?>
