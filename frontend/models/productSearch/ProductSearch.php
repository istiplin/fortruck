<?php
namespace frontend\models\productSearch;

use Yii;
use frontend\models\cart\Cart;
use common\models\Config;
use yii\helpers\Html;
use common\models\Product;

abstract class ProductSearch extends \yii\base\Model
{
    
    //заголовок списка найденных товаров
    public $title;
    
    //ссылка на объект корзина
    private $_cart;
    
    protected $_dataProvider;
    
    //возвращает данные для построения списка товаров с помощью GridView
    abstract public function getDataProvider();
    
    //фабричный метод (определяет какой класс инициализировать)
    public static function initial($text)
    {
        //пытаемся найти товар, рассматривая поисковую строку как артикул
        $productInfo = Product::findOne(['number'=>$text]);
        
        //если товар найден
        if ($productInfo)
            //ищем его аналоги
            return new AnalogProductSearch($productInfo);
        //иначе
        else
            //ищем товары, рассматривая поисковую строку как подстроку артикла или как подстроку наименования товара
            return new TextProductSearch($text);
    }
    
    //возвращает информацию о товаре, который нашли по артиклу
    public function getProductInfo()
    {
        return null;
    }
    
    public function getCart()
    {
        if ($this->_cart!==null)
            return $this->_cart;
        return $this->_cart = Cart::initial();
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
                'value'=>function($data){return $data->producer_name;},
                'headerOptions'=>['class'=>'producer-field-header'],
                'contentOptions'=>['class'=>'producer-field'],
            ],
            [
                'label'=>'Цена',
                'value'=>function($data){return $data->priceView;},
                'format'=>'raw',
                'headerOptions'=>['class'=>'price-field-header'],
                'contentOptions'=>['class'=>'price-field'],
            ],
            [
                'label'=>'Количество',
                'value'=>function($data){return $data->countView;},
                'format'=>'raw',
                'headerOptions'=>['class'=>'count-field-header'],
                'contentOptions'=>['class'=>'count-field'],
            ],          
            [
                'label'=>'В корзине',
                'value'=>function($data){return Cart::initial()->getCountView($data);},
                'format'=>'raw',
                'headerOptions'=>['class'=>'cart-count-field'],
                'contentOptions'=>['class'=>'cart-count-field'],
                'visible'=>!Yii::$app->user->isGuest
            ],        
            [
                'label'=>'Заказ',
                'value'=>function($data){return Cart::initial()->view($data);},
                'format'=>'raw',
                'headerOptions'=>['class'=>'order-field'],
                'contentOptions'=>['class'=>'order-field'],
                'visible'=>!Yii::$app->user->isGuest
            ]
                         
        ];
    }
}
?>
