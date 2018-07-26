<?php
    use yii\grid\GridView;
    use yii\widgets\Pjax;
    use yii\helpers\Html;
?>
<?php if (strlen($search->search)):?>
    <h4>Результаты поиска по запросу "<b><?=$search->search?></b>"</h4>
<?php endif; ?>
<?php // Pjax::begin(); ?>
<?= GridView::widget([
    'dataProvider' => $search->dataProviderForProducts,
    'columns' => [
        [
            'attribute'=>'number',
            'value'=>function($data){
                return Html::a($data->number,['site/search','article'=>$data->number],['title'=>'Посмотреть аналоги']);
            },
            'format'=>'html',
        ],
        'producerName',
        'price',
        [
            'attribute'=>'name',
            'value'=> function($data){
                if (strlen($data->name))
                    return $data->name;
                else
                    return $data->analog->name;
            },
        ],
        
    ],
]);
?>
<?php // Pjax::end(); ?>

