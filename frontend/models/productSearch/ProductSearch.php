<?php
namespace frontend\models\productSearch;

use Yii;
use frontend\models\cart\Cart;
use common\models\Config;
use yii\helpers\Html;

abstract class ProductSearch extends \yii\base\Model
{
    protected $price_coef;
    
    //заголовок списка найденных товаров
    public $title;
    
    //ссылка на объект корзина
    private $_cart;
    
    //возвращает данные для построения списка товаров с помощью GridView
    abstract public function getDataProvider();
    
    public function init()
    {
        $this->price_coef = Config::value('cost_price_coefficient');
    }
    
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
        $coef = Config::value('cost_price_coefficient');
        $query = "select *, cost_price*$coef as price
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
        $cart = $this->cart;
        return [
            [
                'label'=>'Артикул',
                'value'=>function($data){
                    return Html::a($data['number'],['site/search','text'=>$data['number']],['title'=>'Посмотреть аналоги']);
                },
                'format'=>'raw',
            ],
            'name:text:Наименование',
            'producer_name:text:Производитель',
            [
                'label'=>'Цена',
                'value'=>function($data)
                {
                    return sprintf("%01.2f", $data['price']);
                }
            ],
            [
                'label'=>'В корзине',
                'value'=>function($data) use ($cart){
                    return $cart->getCount($data['id']);
                }
            ],        
            [
                'label'=>'Заказ',
                'value'=>function($data) use ($cart){
                    return Html::beginForm('', 'post', ['class' => 'add-to-cart']).
                                Html::hiddenInput('cart[id]', $data['id']).
                                //Html::submitButton('-').
                                Html::input('text', 'cart[count]', $cart->getCount($data['id']),['size'=>1,'class'=>'cart-count']).
                                //Html::submitButton('+').
                                Html::submitButton('',['class'=>'cart-button']).
                            Html::endForm();
                },
                'format'=>'raw',
            ]
                         
        ];
    }
}
?>
