<?php
    use yii\grid\GridView;
    use frontend\model\BagModel;
?>
<?php if($bag->count()):?>
    <h3>Корзина:</h3>
    <?=GridView::widget([
        'dataProvider' => $bag->dataProvider,
        'columns' => [
            [
                'attribute'=>'number',
                'value'=>function($data){
                    return $data->number;
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
                'label'=>'Количество',
                'value'=>function($data) use ($bag){
                    return $bag->count($data->id);
                }
            ]
        ]
    ])
    ?>
<?php else: ?>
    <h3>Корзина пуста.</h3>
<?php endif; ?>
