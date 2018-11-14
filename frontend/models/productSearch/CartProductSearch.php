<?php
namespace frontend\models\productSearch;

use Yii;
use yii\data\ArrayDataProvider;
use yii\data\SqlDataProvider;
use yii\helpers\Html;
use frontend\models\cart\Cart;

//класс для вывода продуктов, которые в корзине
class CartProductSearch extends ProductSearch
{
    public function __construct()
    {
        parent::init();
        $this->title = "<h3>Корзина:</h3>";
    }
    
    public function getDataProvider()
    {
        if ($this->cart->typeCount==0)
            return new ArrayDataProvider;
        
        $implodedId = implode(',',$this->cart->listId);
        $sql = "select *, price
                from product
                where id in($implodedId) and price>0";

        $dataProvider = new SqlDataProvider([
           'sql' => $sql,
           'totalCount' => $this->cart->typeCount,
           'pagination' => false,
        ]);
        
        return $dataProvider;
    }
    
    public function getColumns()
    {
        return [
            [
                'label'=>'Артикул',
                'value'=>function($data){
                    return Html::a($data['number'],['site/search','text'=>$data['number']],['title'=>'Посмотреть аналоги']);
                },
                'format'=>'raw',
            ],
            'name:text:Наименование',
            [
                'label'=>'Производитель',
                'value'=>function($data)
                {
                    return $data['producer_name'];
                },
                'headerOptions'=>['class'=>'producer-field-header'],
                'contentOptions'=>['class'=>'producer-field'],
            ],
            [
                'label'=>'Цена',
                'value'=>function($data)
                {
                    return sprintf("%01.2f", $data['price']);
                },
                'format'=>'raw',
                'headerOptions'=>['class'=>'price-field-header'],
                'contentOptions'=>['class'=>'price-field'],
            ],
            [
                'label'=>'В корзине',
                'value'=>function($data){
                    return "<span class='cart-count-value' data-id={$data['id']}>".Cart::initial()->getCount($data['id'])."</span>";
                },
                'format'=>'raw',
                'headerOptions'=>['class'=>'cart-count-field'],
                'contentOptions'=>['class'=>'cart-count-field'],
                'visible'=>!Yii::$app->user->isGuest
            ],        
            [
                'label'=>'Заказ',
                'value'=>function($data){
                    $count = Cart::initial()->getCount($data['id']);

                    return "<div class='add-to-cart'>".
                                Html::button('-', ['class'=>'minus-button']).
                                Html::input('text', 'cart[count]', $count,['size'=>1,'class'=>'cart-count','data-id'=>$data['id']]).
                                Html::button('+', ['class'=>'plus-button']).
                                Html::submitButton('',['class'=>'cart-button']).
                            "</div>";
                },
                'format'=>'raw',
                'headerOptions'=>['class'=>'order-field'],
                'contentOptions'=>['class'=>'order-field'],
                'visible'=>!Yii::$app->user->isGuest
            ]
                         
        ];
    }
}
?>
