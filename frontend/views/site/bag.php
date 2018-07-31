<?php
    use yii\grid\GridView;
    use frontend\model\BagModel;
    use yii\helpers\Html;
?>
<?php if($bag->count()):?>
    <h3>Корзина:</h3>
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
    <?=Html::beginForm('', 'post')?>
        <?=Html::submitButton('Оформить заказ', ['name'=>'form_order','class' => 'btn btn-primary'])?>
    <?=Html::endForm()?>
<?php else: ?>
    <h3>Корзина пуста.</h3>
<?php endif; ?>
