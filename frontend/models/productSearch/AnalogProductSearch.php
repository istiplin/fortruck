<?php
namespace frontend\models\productSearch;

use Yii;
use yii\data\SqlDataProvider;
use yii\helpers\Html;
use frontend\models\cart\Cart;

//класс для поиска продуктов по продукту-аналогу
class AnalogProductSearch extends ProductSearch
{
    private $_productInfo;
    
    public function __construct($productInfo,Cart $cart=null)
    {
        parent::init();
        $this->_cart = $cart;
        $this->_productInfo = $productInfo;
        $this->title = "<h3>Аналоги для <b>{$this->_productInfo['analogName']}</b>:</h3>";
    }
    
    public function getProductInfo()
    {
        return $this->_productInfo;
    }
    
    public function getDataProvider()
    {
        $selectCount = "";
        $joinCart = "";
        $andWhere = "";

        if (!Yii::$app->user->isGuest)
        {
            $selectCount = ",b.count ";
            $joinCart = " left join cart b on b.product_id = p.id ";
            $andWhere = " and (b.user_id is null or b.user_id=".Yii::$app->user->identity->id.")";
        }
        
        $sql = "select
                    p.id,
                    p.number,
                    p.cost_price*{$this->price_coef} as price,
                    p.name as productName,
                    pr.name as producerName,
                    a.name as analogName
                    $selectCount
                from product p
                join analog a on a.id = p.analog_id
                join producer pr on pr.id = p.producer_id
                $joinCart
                where p.analog_id={$this->_productInfo['analogId']} and p.id<>{$this->_productInfo['id']}
                $andWhere";

        $sqlCount = "select 
                        count(*)
                    from product
                    where analog_id={$this->_productInfo['analogId']} and id<>{$this->_productInfo['id']}";
                
        $count = Yii::$app->db->createCommand($sqlCount)->queryScalar();
                
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => $count,
            //'pagination' =>[
            //    'pageSize' => 2,
            //]
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
            [
                'label'=>'Корзина',
                'value'=>function($data) use ($cart){
                    
                    if (Yii::$app->user->isGuest)
                        $count = $cart->count($data['id']);
                    else
                        $count = $data['count'] ?? 0;
                        
                    return Html::beginForm('', 'post', ['class' => 'add-to-cart']).
                                Html::hiddenInput('cart[id]', $data['id']).
                            
                                //Html::submitButton('-').
                                Html::input('text', 'cart[count]', $count,['size'=>1,'class'=>'cart-count']).
                                //Html::submitButton('+').
                            
                                Html::submitButton('',['class'=>'cart-button']).
                            Html::endForm().
                            $cart->message($data['id']);
                },
                'format'=>'raw',
            ]
        ];
    }
}
?>
