<?php
    use yii\widgets\DetailView;
    use yii\grid\GridView;
    use yii\widgets\Pjax;
    use yii\web\View;
    use yii\helpers\Html;
    use frontend\models\cart\Cart;
?>

<?php if ($search === null):?>
    Задан пустой поисковый запрос.
<?php else: ?>

    <!-- информация о найденном товаре по артиклу -->
    <?php if($search->productInfo AND $search->productInfo['is_visible']):?>
        <h3>Найденный товар:</h3>
        <?= DetailView::widget([
            'model' => $search->productInfo,
            'attributes' => [
                'number:text:Артикул',
                'name',
                'producer_name',
                'priceView:raw',
                'countView',
                [
                    'label'=>'В корзине',
                    'value'=>function($data){return Cart::initial()->getCountView($data);},
                    'visible'=>!Yii::$app->user->isGuest,
                    'format'=>'raw'
                ],
                [
                    'label'=>'Заказ',
                    'value'=>function($data){return Cart::initial()->view($data);},
                    'visible'=>!Yii::$app->user->isGuest,
                    'format'=>'raw',
                ]
            ],
        ]) ?>
    <?php endif; ?>

    <!-- список найденных товаров -->
    <?=$search->title?>
    <?php Pjax::begin([
        'linkSelector'=>'.pagination a',
        //'formSelector'=>'.add-to-cart',
        'enablePushState' => false,
        'id' => 'pjax-products'
    ]); ?>

        <?=GridView::widget([
            'dataProvider' => $search->dataProvider,
            'columns' => $search->columns,
            'layout'=>"{pager}\n{items}\n{pager}",
            ]);
        ?>

    <?php Pjax::end(); ?>
    
<?php endif; ?>