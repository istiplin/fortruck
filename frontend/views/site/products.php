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
            'attribute'=>'number',
            'value'=>function($data){
                return Html::a($data->number,['site/search','article'=>$data->number],['title'=>'Посмотреть аналоги']);
            },
            'format'=>'raw',
        ],
        'producerName',
        'price',
        [
            'attribute'=>'name',
            'value'=>function($data){
                if (strlen($data->name))
                    return $data->name;
                else
                    return $data->analog->name;
            },
        ],
        [
            'attribute'=>'addToBasket',
            'label'=>'Корзина',
            'value'=>function($data) use ($basket){
                return Html::beginForm('', 'post', ['class' => 'add-to-branch']).
                            Html::hiddenInput('basket[id]', $data->id).
                            Html::input('text', 'basket[count]', $basket[$data->id] ?? 0,['size'=>1]).
                            Html::submitButton('В корзину').
                        Html::endForm();
            },
            'format'=>'raw',
        ]
        
    ],
]);
?>
<?php Pjax::end(); ?>