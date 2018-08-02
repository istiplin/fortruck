<?php
namespace frontend\models\productSearch;

use Yii;
use yii\data\SqlDataProvider;
use yii\helpers\Html;
use frontend\models\bag\Bag;

//класс для поиска продуктов по корзине
class BagProductSearch extends ProductSearch
{
    public function __construct()
    {
        $this->_bag = Bag::initial();
        $this->title = "<h3>Корзина:</h3>";
    }
    
    public function getDataProvider()
    {

        if (Yii::$app->user->isGuest)
        {
            $selectCount = "";
            $joinBag = "";
            
            $where = " where p.id is null ";
            if (count($this->_bag->productIds))
                $where = " where p.id in(".implode(',',$this->_bag->productIds).") ";
        }
        else
        {
            $selectCount = ",b.count ";
            $joinBag = " join bag b on b.product_id = p.id ";
            $where = " where b.user_id=".Yii::$app->user->identity->id;
            
        }
        
        $sql = "select
                    p.id,
                    p.number,
                    p.price,
                    p.name as productName,
                    pr.name as producerName,
                    a.name as analogName
                    $selectCount
                from product p
                join analog a on a.id = p.analog_id
                join producer pr on pr.id = p.producer_id
                $joinBag
                $where";

        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => $this->_bag->typeCount,
            'pagination' => false,
            //'pagination' =>[
            //    'pageSize' => 2,
            //]
        ]);
        
        return $dataProvider;
    }
  
    public function getColumns()
    {
        $bag = $this->_bag;
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
            [
                'label'=>'Корзина',
                'value'=>function($data) use ($bag){
                    
                    if (Yii::$app->user->isGuest)
                        $count = $bag->count($data['id']);
                    else
                        $count = $data['count'] ?? 0;
                    
                    return Html::beginForm('', 'post', ['class' => 'add-to-bag']).
                                Html::hiddenInput('bag[id]', $data['id']).
                                Html::input('text', 'bag[count]', $count,['size'=>1]).
                                Html::submitButton('Обновить').
                            Html::endForm().
                            $bag->message($data['id']);
                },
                'format'=>'raw',
            ]
        ];
    }
        
    /*
    //возвращет цены на товары в корзине
    public function getProductsInfo()
    {
        $products_id = array_keys($this->_bag);
        $productsInfo = Product::find()->select('price, id')->where('id in('.implode(',',$products_id).')')->indexBy('id')->asArray()->all();
        foreach($this->_bag as $id=> $count)
            $productsInfo[$id]['count'] = $count;
        return $productsInfo;
    }
     * 
     */
        
}
?>
