<?php

namespace frontend\models;

//Класс для предоставления данных о товарах полученных по текстовому поиску
abstract class LookupProducts extends Products
{
    public static function initialProducts($number,$isRemote)
    {
        if ($isRemote)
            return new RemoteLookupProducts($number);
        return new LocalLookupProducts($number);
    }
    
    public function __construct($number)
    {
        $this->_number = $number;
    }
    
    public function getTitle(){
        if ($this->_title!==null)
            return $this->_title;
        
        $this->_title = '';
        if (strlen($this->_number))
            $this->_title = "<h4>Результаты поиска по запросу <b>'{$this->_number}'</b></h4>";
        
        return $this->_title;
    }
    
    public function getColumns() {
        return [
            [
                'attribute'=>'number',
                'value' => function($data) {
                    return \yii\helpers\Html::a($data['number'], ['site/search', 'number' => $data['number'], 'brandName' => $data['brandName']], ['title' => 'Посмотреть аналоги']);
                },
                'format' => 'raw',
            ],
            [
                'attribute'=>'brandName',
                'headerOptions' => ['class' => 'producer-field-header'],
                'contentOptions' => ['class' => 'producer-field'],
            ],
            'name',
        ];
    }
    
    public function getRowOptions(){
        return function($data){
            return ['data-norm-number'=>mb_strtoupper($data->norm_number),
                    'data-brand'=>mb_strtoupper($data->brandName), 
                    'class'=>'product-data'];};
    }
    
    public function getItemOptions(){
        return function($data){
            return ['data-norm-number'=>mb_strtoupper($data->norm_number),
                    'data-brand'=>mb_strtoupper($data->brandName), 
                    'tag' => 'div',
                    'class'=>'product-data'];};
    }
}
