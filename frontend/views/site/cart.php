<?php

use yii\grid\GridView;
use frontend\model\CartModel;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use frontend\models\cart\Cart;
?>

<?php if ($search->dataProvider->totalCount): ?>
    <?php if(!Yii::$app->request->isAjax):?>
        <?= $search->title ?>
    <?php endif; ?>

    <?=GridView::widget([
        'rowOptions' => function($data){return [
                                    'data-number'=>mb_strtoupper($data->number),
                                    'data-brand'=>mb_strtoupper($data->brandName), 
                                    'class'=>'product-data'];},
        'dataProvider' => $search->dataProvider,
        'columns' => $search->columns
    ])
    ?>

    <h4><b>Итого:</b> <span class="moneySumm"><?=Cart::initial()->priceSum ?></span></h4>

    <?= Html::beginForm('', 'post', ['id' => 'checkout-form']) ?>
    <?= Html::submitButton('Оформить заказ', ['name' => 'form_order', 'class' => 'btn btn-primary']) ?>
    <?= Html::endForm() ?>

<?php else: ?>
    <h3>Корзина пуста.</h3>
<?php endif; ?>