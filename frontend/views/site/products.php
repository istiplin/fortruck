<?php
    use yii\widgets\DetailView;
    use yii\grid\GridView;
    use frontend\models\cart\Cart;
    use frontend\models\OffersProducts;
?>

<?php if ($search === false):?>
    Задан пустой поисковый запрос.
<?php else: ?>

    <!-- информация о найденном товаре по артиклу -->
    <?php if($search AND $search instanceof OffersProducts AND $search->oneInfo AND $search->oneInfo->is_visible):?>
        <h3>Найденный товар:</h3>
        <?= DetailView::widget([
            'id' => 'target',
            'options' => [
                'data-number'=>mb_strtoupper($search->oneInfo->number),
                'data-brand'=>mb_strtoupper($search->oneInfo->brandName),
                'class' => 'product-data table table-striped table-bordered detail-view'],
            'model' => $search->oneInfo,
            'attributes' => [
                'number',
                'brandName',
                'name',
                [
                    'attribute'=>'custPriceView',
                    'format' => 'raw',
                ],
                'countView',
                [
                    'label'=>'В корзине',
                    'value'=>function($data){return Cart::initial()->getCountView($data,'target');},
                    'visible'=>!Yii::$app->user->isGuest,
                    'format'=>'raw'
                ],
                [
                    'label'=>'Заказ',
                    'value'=>function($data){return Cart::initial()->view($data,'target');},
                    'visible'=>!Yii::$app->user->isGuest,
                    'format'=>'raw',
                ]
            ],
        ]) ?>
    <?php endif; ?>

    <?php if($search):?>
        <?php if ($search->dataProvider->totalCount): ?>
            <!-- список найденных товаров -->
            <?=$search->title?>
            <?=GridView::widget([
                'id' => 'analogs',
                'rowOptions' => function($data){return [
                                                'data-number'=>mb_strtoupper($data->number),
                                                'data-brand'=>mb_strtoupper($data->brandName), 
                                                'class'=>'product-data'];},
                'dataProvider' => $search->dataProvider,
                'columns' => $search->columns,
                'layout'=>"{pager}\n{items}",
                ]);
            ?>
        <?php else: ?>
            <?php if($search instanceof OffersProducts): ?>
                Аналогов не найдено.
            <?php else:?>
                Ничего не найдено.
            <?php endif; ?>    
        <?php endif; ?>
    <?php endif; ?> 
                
<?php endif; ?>