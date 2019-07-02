<?php
    use yii\widgets\DetailView;
    use yii\grid\GridView;
    use yii\widgets\ListView;
    use frontend\models\OffersProducts;
    use common\widgets\requestPrice\RequestPriceWidget;
    use yii\bootstrap\Modal;
?>
<!-- информация о найденном товаре по артиклу -->
<?php if($search->oneInfo AND $search->oneInfo->is_visible):?>
    <h4>Найденный товар:</h4>
    <?= DetailView::widget([
        'id' => 'target',
        'options' => [
            'data-norm-number'=>mb_strtoupper($search->oneInfo->number),
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
                'attribute'=>'cartCountView',
                'visible'=>!Yii::$app->user->isGuest,
                'format'=>'raw'
            ],
            [
                'attribute'=>'cartView',
                'visible'=>!Yii::$app->user->isGuest,
                'format'=>'raw',
            ]
        ],
    ]) ?>
<?php endif; ?>

<?php if ($search->dataProvider->totalCount): ?>
    <?=$search->title?>
    <?php
        $header = $this->render('_headerOffers',compact('search'));

        echo ListView::widget([
            'options' => ['class'=>'list-view'.(Yii::$app->user->isGuest?' not-cart':'')],
            'dataProvider' => $search->dataProvider,
            'layout' => "{summary}$header\n{items}\n{pager}",
            'itemView' => ($search instanceof OffersProducts)?'_itemOffers':'_itemLookup',
            'itemOptions' => $search->itemOptions,
        ]);
    ?>
<?php else: ?>
    Аналогов не найдено. 
<?php endif; ?>

<?php
    $this->registerCssFile('@web/css/list_view_style.css');
    Modal::begin([
        'header'=>'<h3>Корзина:</h3>',
        'id'=>'cart-modal',
        'size'=>Modal::SIZE_LARGE,
    ]);
    Modal::end(); 
?>

<?php if(Yii::$app->user->isGuest): ?>
    <?= RequestPriceWidget::widget([
                        'id'=>'request-price',
                        'activeFormConfig'=>[
                                                'action'=>['site/request-price'],
                                                //'enableClientValidation' => true,
                                ]]); 
    ?>

<?php endif; ?>
