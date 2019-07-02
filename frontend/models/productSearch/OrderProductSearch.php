<?php
namespace frontend\models\productSearch;

use Yii;
use yii\data\SqlDataProvider;
use common\models\OrderItem;
use frontend\models\Product;

//класс для вывода заказанных продуктов
class OrderProductSearch extends \yii\base\Component
{
    public $title;
    private $id;
    private $_dataProvider;
    private $_product;
    
    public function __construct($id)
    {
        $this->id = $id;
        $this->title = "<h3>Заказ №$id</h3>";
    }
    
    public function getProduct()
    {
        if ($this->_product!==null)
            return $this->_product;
        return $this->_product = new Product();
    }
    
    public function getDataProvider()
    {
        $sql = 'select
                    p.name,
                    p.number,
                    b.name as brandName,
                    oi.price,
                    oi.count
                from order_item oi
                join product p on p.id = oi.product_id
                join `order` o on o.id = oi.order_id
                join brand b on b.id = p.brand_id
                where o.user_id='.Yii::$app->user->identity->id.' and oi.order_id='.$this->id;

        $sqlCount = "select count(*) 
                    from order_item
                    where order_id=".$this->id;
        
        $count = Yii::$app->db->createCommand($sqlCount)->queryScalar();

        $provider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => $count,
        ]);
        
        $models = $provider->getModels();

        foreach ($models as &$model)
            $model = new Product($model);

        $provider->setModels($models);
        
        return $this->_dataProvider = $provider;
    }
  
    public function getColumns()
    {
        return [
            'number:text:Артикул',
            'brandName:text:Бренд',
            'name:text:Наименование',
            'price:text:Цена',
            'count:text:Количество',
        ];
    }
    
    public function getPriceSum()
    {
        $priceSum = OrderItem::find()
            ->joinWith(['order'])
            ->where(['order_id'=>$this->id, 'user_id'=>Yii::$app->user->identity->id])
            ->sum('price*count');
        return (($priceSum)?sprintf("%01.2f", $priceSum):sprintf("%01.2f", 0)).' руб.';
    }
    
    public function getAttributeLabel($attribute)
    {
        return $this->product->getAttributeLabel($attribute);
    }
}
?>
