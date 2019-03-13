<?php

namespace frontend\models;

//Класс для предоставления данных об аналогах полученых по искомому товару
abstract class OffersProducts extends Products
{
    protected $_brandName;
    
    public static function initialProducts($number,$brandName,$isRemote)
    {
        if ($isRemote)
            return new RemoteOffersProducts($number,$brandName);
        return new LocalOffersProducts($number,$brandName);
    }
    
    public function __construct($number,$brandName)
    {
        $this->_number = $number;
        $this->_brandName = $brandName;
        
        
        $this->title = "<h3>Аналоги для <b>$number ($brandName)</b>:</h3>";
    }

    public function getColumns() {
        return [
            'number',
            [
                'attribute'=>'brandName',
                'headerOptions' => ['class' => 'producer-field-header'],
                'contentOptions' => ['class' => 'producer-field'],
            ],
            'name',
            [
                'attribute'=>'custPriceView',
                'format' => 'raw',
                'headerOptions' => ['class' => 'price-field-header'],
                'contentOptions' => ['class' => 'price-field'],
            ],
            [
                'attribute'=>'countView',
                'headerOptions' => ['class' => 'count-field-header'],
                'contentOptions' => ['class' => 'count-field'],
            ],
            [
                'label' => 'В корзине',
                'value' => function($data) {
                    return \frontend\models\cart\Cart::initial()->getCountView($data);
                },
                'format' => 'raw',
                'headerOptions' => ['class' => 'cart-count-field'],
                'contentOptions' => ['class' => 'cart-count-field'],
                'visible' => !\Yii::$app->user->isGuest
            ],
            [
                'label' => 'Заказ',
                'value' => function($data) {
                    return \frontend\models\cart\Cart::initial()->view($data);
                },
                'format' => 'raw',
                'headerOptions' => ['class' => 'order-field'],
                'contentOptions' => ['class' => 'order-field'],
                'visible' => !\Yii::$app->user->isGuest
            ]
        ];
    }
}
