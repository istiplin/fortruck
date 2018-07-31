<?php
    use yii\grid\GridView;
    use yii\widgets\Pjax;
    use yii\helpers\Html;
?>
<?php if (strlen($search->search)):?>
    <h4>Результаты поиска по запросу "<b><?=$search->search?></b>"</h4>
<?php endif; ?>
<?php Pjax::begin([
    'linkSelector'=>'.pagination a',
    'formSelector'=>'.add-to-branch'
]); ?>
<?=GridView::widget([
    'dataProvider' => $search->dataProviderForProducts,
    'columns' => [
        [
            'label'=>'Артикул',
            'value'=>function($data){
                return Html::a($data['number'],['site/search','article'=>$data['number']],['title'=>'Посмотреть аналоги']);
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
        'price:text:Цена',
                    
        [
            'label'=>'Корзина',
            'value'=>function($data) use ($bag){
                return Html::beginForm('', 'post', ['class' => 'add-to-branch']).
                            Html::hiddenInput('bag[id]', $data['id']).
                            Html::input('text', 'bag[count]', $bag->count($data['id']) ?? 0,['size'=>1]).
                            Html::submitButton('В корзину').
                        Html::endForm().
                        $bag->message($data['id']) ?? '';
            },
            'format'=>'raw',
        ]
    ],
]);
?>
<?php Pjax::end(); ?>