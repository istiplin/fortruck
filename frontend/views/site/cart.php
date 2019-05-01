<?php
use yii\grid\GridView;
use yii\widgets\ListView;
use yii\helpers\Html;
use frontend\models\cart\Cart;
use yii\bootstrap\Modal;
?>

<?php if ($search->dataProvider->totalCount): ?>
    <?php if(!Yii::$app->request->isAjax):?>
        <?= $search->title ?>
    <?php endif; ?>

    <?php /*echo GridView::widget([
        'rowOptions' => $search->rowOptions,
        'dataProvider' => $search->dataProvider,
        'columns' => $search->columns
    ])
     * 
     */
        $header = "<div class='row-header'>
<div class='number'>{$search->getAttributeLabel('number')}</div>
<div class='brand'>{$search->getAttributeLabel('brandName')}</div>
<div class='name'>{$search->getAttributeLabel('name')}</div>
<div class='price'>{$search->getAttributeLabel('custPriceView')}</div>
<div class='cart-qty'>{$search->getAttributeLabel('cartCountView')}</div>
<div class='add-to-cart'>{$search->getAttributeLabel('cartView')}</div>
</div>";
        echo ListView::widget([
            'options' => ['class' => 'list-view cart-list-view'],
            'dataProvider' => $search->dataProvider,
            'layout' => "{summary}$header\n{items}\n{pager}",
            'itemView' => '_itemCart',
            'itemOptions' => $search->itemOptions,
        ]);
    ?>

    <?php if (Cart::initial()->priceSum):?>
        <h4><b>Итого:</b> <span class="moneySumm"><?=Cart::initial()->priceSumView ?></span></h4>
        <?php echo Html::submitButton('Оформить заказ', ['name' => 'form_order', 
                                            'class' => 'btn btn-primary',
                                            'data-toggle'=>'modal',
                                            'data-target'=>'#checkout-modal']) ?>
    <?php endif; ?>

<?php else: ?>
    <h3>Корзина пуста.</h3>
<?php endif; ?>