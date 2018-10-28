<?php
namespace frontend\models\productSearch;

use Yii;
use frontend\models\cart\Cart;
use common\models\Config;
use yii\helpers\Html;

abstract class ProductSearch extends \yii\base\Model
{
    
    //заголовок списка найденных товаров
    public $title;
    
    //ссылка на объект корзина
    private $_cart;
    
    //возвращает данные для построения списка товаров с помощью GridView
    abstract public function getDataProvider();
    
    //фабричный метод (определяет какой класс инициализировать)
    public static function initial($text)
    {
        //пытаемся найти товар, рассматривая поисковую строку как артикул
        $productInfo = self::_getProductInfo($text);
        
        //если товар найден
        if ($productInfo)
            //ищем его аналоги
            return new AnalogProductSearch($productInfo);
        //иначе
        else
            //ищем товары, рассматривая поисковую строку как подстроку артикла или как подстроку наименования товара
            return new TextProductSearch($text);
    }
    
    //находит товар, рассматривая поисковую строку как артикул
    private static function _getProductInfo($text)
    {
        $query = "select *
                from product
                where number=:text";

        return Yii::$app->db->createCommand($query,[':text'=>$text])->queryOne();
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
                'contentOptions'=>['class'=>'order-field']
            ]
                         
        ];
    }
}
?>
