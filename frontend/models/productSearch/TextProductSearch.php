<?php
namespace frontend\models\productSearch;

use Yii;
use yii\data\SqlDataProvider;
use yii\helpers\Html;
use frontend\models\cart\Cart;

//класс для поиска продуктов по тексту
class TextProductSearch extends ProductSearch
{
    private $_text;
    
    public function __construct($text,Cart $cart=null)
    {
        parent::init();
        $this->_cart = $cart;
        $this->_text = $text;
        $this->title='';
        if (strlen($this->_text))
            $this->title = "<h4>Результаты поиска по запросу <b>'{$this->_text}'</b></h4>";
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
                where (p.number like :text or p.name like :text or a.name like :text) 
                $andWhere";

        $sqlCount = "select 
                        count(*)
                    from product p
                    join analog a on a.id = p.analog_id
                    where p.number like :text or p.name like :text or a.name like :text";
        
        $count = Yii::$app->db->createCommand($sqlCount,[':text'=>"%{$this->_text}%"])->queryScalar();
                
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'params' => [':text'=>"%{$this->_text}%"],
            'totalCount' => $count,
            //'pagination' =>[
            //    'pageSize' => 2,
            //]
        ]);
        //print_r($dataProvider); die();
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
            [
                'label'=>'Цена',
                'value'=>function($data)
                {
                    return sprintf("%01.2f", $data['price']);
                }
            ],
            
                        
            [
                'label'=>'Заказ',
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
                                $cart->message($data['id']).
                            Html::endForm();
                            
                },
                'format'=>'raw',
            ]
                         
        ];
    }
}
?>
