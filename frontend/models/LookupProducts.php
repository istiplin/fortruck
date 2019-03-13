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
        
        $this->title = '';
        if (strlen($number))
            $this->title = "<h4>Результаты поиска по запросу <b>'$number'</b></h4>";
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
}
