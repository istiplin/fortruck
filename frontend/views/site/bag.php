<?php
    use yii\grid\GridView;
    use frontend\model\BagModel;
    use yii\helpers\Html;
    use yii\widgets\Pjax;
?>

<?php Pjax::begin([
    'linkSelector'=>'.pagination a',
    'formSelector'=>'.add-to-bag'
]); ?>
    <?php if($search->dataProvider->totalCount):?>
        <?=$search->title?>

            <?=GridView::widget([
                'dataProvider' => $search->dataProvider,
                'columns' => $search->columns
            ])
            ?>

        <?=Html::beginForm('', 'post')?>
            <?=Html::submitButton('Оформить заказ', ['name'=>'form_order','class' => 'btn btn-primary'])?>
        <?=Html::endForm()?>

    <?php else: ?>
        <h3>Корзина пуста.</h3>
    <?php endif; ?>
<?php Pjax::end(); ?>
    
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