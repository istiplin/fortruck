<?php
    use yii\widgets\DetailView;
    use yii\grid\GridView;
    use yii\widgets\Pjax;
    use yii\helpers\Html;
?>

<?php if($search->productInfo):?>

    <h4>Найденный товар:</h4>
    <?= DetailView::widget([
        'model' => $search->productInfo,
        'attributes' => [
            [
                'label'=>'Артикул',
                'value'=>function($data){
                    return $data['number'];
                }
            ],
            [
                'label'=>'Наименование',
                'value'=>function($data){
                    return $data['name'];
                }
            ],
            [
                'label'=>'Производитель',
                'value'=>function($data){
                    return $data['producerName'];
                }
            ],
            [
                'label'=>'Цена',
                'value'=>function($data){
                    return $data['price'];
                }
            ]
        ],
    ]) ?>
    <h4>Аналоги для <b><?=$search->productInfo['analogName']?></b>:</h4>

    <?php Pjax::begin([
    'linkSelector'=>'.pagination a',
    'formSelector'=>'.add-to-branch'
]); ?>
    <?= GridView::widget([
        'dataProvider' => $search->dataProviderForAnalog,
        'columns' => [

            'number',
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
            [
                'attribute'=>'addToBag',
                'label'=>'Корзина',
                'value'=>function($data) use ($bag){
                    return Html::beginForm('', 'post', ['class' => 'add-to-branch']).
                                Html::hiddenInput('bag[id]', $data->id).
                                Html::input('text', 'bag[count]', $bag->count($data->id) ?? 0,['size'=>1]).
                                Html::submitButton('В корзину').
                            Html::endForm().
                            $bag->message($data->id) ?? '';
                },
                'format'=>'raw',
            ]
        ],
    ]); ?>
            
    <?php Pjax::end(); ?>
    
<?php else: ?>
    <?php echo $this->render('products',compact('search','bag')); ?>
<?php endif; ?>
