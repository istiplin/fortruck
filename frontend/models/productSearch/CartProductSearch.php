<?php
namespace frontend\models\productSearch;

use Yii;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use frontend\models\cart\Cart;
use common\models\Product;

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
        if ($this->_dataProvider!==null)
            return $this->_dataProvider;
        
        if ($this->cart->typeCount==0)
            return $this->_dataProvider = new ArrayDataProvider;
        
        $implodedId = implode(',',$this->cart->listId);
        
        $query = Product::find()->where("id in($implodedId)")->andWhere(['>','price',0]);
        
        $this->_dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
        ]);
        
        return $this->_dataProvider;
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
                'value'=>function($data){return $data['producer_name'];},
                'headerOptions'=>['class'=>'producer-field-header'],
                'contentOptions'=>['class'=>'producer-field'],
            ],
            [
                'label'=>'Цена',
                'value'=>function($data){return $data['price'];},
                'format'=>'raw',
                'headerOptions'=>['class'=>'price-field-header'],
                'contentOptions'=>['class'=>'price-field'],
            ],
            [
                'label'=>'В корзине',
                'value'=>function($data){return Cart::initial()->getCountView($data,false);},
                'format'=>'raw',
                'headerOptions'=>['class'=>'cart-count-field'],
                'contentOptions'=>['class'=>'cart-count-field'],
                'visible'=>!Yii::$app->user->isGuest
            ],        
            [
                'label'=>'Заказ',
                'value'=>function($data){return Cart::initial()->view($data,false);},
                'format'=>'raw',
                'headerOptions'=>['class'=>'order-field'],
                'contentOptions'=>['class'=>'order-field'],
                'visible'=>!Yii::$app->user->isGuest
            ]
                         
        ];
    }
}
?>
