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
    }
    
    public function getTitle(){
        if ($this->_title!==null)
            return $this->_title;
        
        return $this->_title = "<h4>Аналоги для <b>{$this->_number} ({$this->_brandName})</b>:</h4>";
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
                'attribute'=>'cartCountView',
                'format' => 'raw',
                'headerOptions' => ['class' => 'cart-count-field'],
                'contentOptions' => ['class' => 'cart-count-field'],
                'visible' => !\Yii::$app->user->isGuest
            ],
            [
                'attribute'=>'cartView',
                'format' => 'raw',
                'headerOptions' => ['class' => 'order-field'],
                'contentOptions' => ['class' => 'order-field'],
                'visible' => !\Yii::$app->user->isGuest
            ]
        ];
    }
    
    public function getRowOptions(){
        return function($data){
            return ['data-number'=>mb_strtoupper($data->number),
                    'data-brand'=>mb_strtoupper($data->brandName), 
                    'class'=>'product-data '.($data->isAvailable?'':'not-available')];};
    }
    
    public function getItemOptions(){
        return function($data){
            return ['data-number'=>mb_strtoupper($data->number),
                    'data-brand'=>mb_strtoupper($data->brandName), 
                    'tag' => 'div',
                    'class'=>'product-data'.($data->isAvailable?'':' not-available')];};
    }
}
