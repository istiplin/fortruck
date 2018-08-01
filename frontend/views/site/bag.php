<?php
    use yii\grid\GridView;
    use frontend\model\BagModel;
    use yii\helpers\Html;
    use yii\widgets\Pjax;
?>
<?php if($bag->count()):?>
    <h3>Корзина:</h3>
    
    <?php Pjax::begin([
        'linkSelector'=>'#p0 .pagination a',
    ]); ?>
        <?=GridView::widget([
            'dataProvider' => $bag->dataProvider,
            'columns' => [
                'number:text:Артикул',
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
                    'label'=>'Количество',
                    'value'=>function($data) use ($bag){
                        return $bag->count($data['id']);
                    }
                ]
            ]
        ])
        ?>
    <?php Pjax::end(); ?>
    
    <?=Html::beginForm('', 'post')?>
        <?=Html::submitButton('Оформить заказ', ['name'=>'form_order','class' => 'btn btn-primary'])?>
    <?=Html::endForm()?>
    
<?php else: ?>
    <h3>Корзина пуста.</h3>
<?php endif; ?>
    
<h3>Мои заказы:</h3>
<?php Pjax::begin([
    'linkSelector'=>'#p1 .pagination a',
]); ?>
    <?=GridView::widget([
            'dataProvider' => $order->dataProvider,
            'columns' => [
                'created_at',
                'updated_at',
                'is_complete',
                'user_id'
            ]
        ])
    ?>
<?php Pjax::end(); ?>