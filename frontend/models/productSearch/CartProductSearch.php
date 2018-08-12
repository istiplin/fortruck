<?php
namespace frontend\models\productSearch;

use Yii;
use yii\data\SqlDataProvider;
use yii\helpers\Html;
use frontend\models\cart\Cart;

//класс для вывода продуктов, которые в корзине
class CartProductSearch extends ProductSearch
{
    public function __construct()
    {
        parent::init();
        $this->_cart = Cart::initial();
        $this->title = "<h3>Корзина:</h3>";
    }
    
    public function getDataProvider()
    {

        if (Yii::$app->user->isGuest)
        {
            $selectCount = "";
            $joinCart = "";
            
            $where = " where p.id is null ";
            if (count($this->_cart->productIds))
                $where = " where p.id in(".implode(',',$this->_cart->productIds).") ";
        }
        else
        {
            $selectCount = ",b.count ";
            $joinCart = " join cart b on b.product_id = p.id ";
            $where = " where b.user_id=".Yii::$app->user->identity->id;
            
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
                $where";

        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => $this->_cart->typeCount,
            'pagination' => false,
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
            [
                'label'=>'Артикул',
                'value'=>function($data){
                    return Html::a($data['number'],['site/search','text'=>$data['number']],['title'=>'Посмотреть аналоги']);
                },
                'format'=>'raw',
            ],
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
                'label'=>'Заказ',
                'value'=>function($data) use ($cart){
                    
                    if (Yii::$app->user->isGuest)
                        $count = $cart->count($data['id']);
                    else
                        $count = $data['count'] ?? 0;
                    
                    return Html::beginForm('', 'post', ['class' => 'add-to-cart']).
                                Html::hiddenInput('cart[id]', $data['id']).
                                Html::input('text', 'cart[count]', $count,['size'=>1,'class'=>'cart-count']).
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
