<?php
    use yii\grid\GridView;
    use frontend\model\CartModel;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\Pjax;
?>

<?php Pjax::begin([
    'linkSelector'=>'.pagination a',
]); ?>
    <?php if($search->dataProvider->totalCount):?>
        <?=$search->title?>

        <?=GridView::widget([
            'dataProvider' => $search->dataProvider,
            'columns' => $search->columns
        ])
        ?>

        <h4><b>Итого:</b> <?=$search->cart->priceSum?></h4>

        <?=Html::beginForm('', 'post')?>
            <?=Html::submitButton('Оформить заказ', ['name'=>'form_order','class' => 'btn btn-primary'])?>
        <?=Html::endForm()?>

    <?php else: ?>
        <h3>Корзина пуста.</h3>
    <?php endif; ?>
<?php Pjax::end(); ?>
    

