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
    <div data-brand='<?=$search->oneInfo->brandName?>' data-norm-number='<?=$search->oneInfo->norm_number?>'>
        <?=$this->render('_itemOffers',['model'=>$search->oneInfo]);?>
    </div>
<?php endif; ?>

<?php if ($search->dataProvider->totalCount): ?>
    <?=$search->title?>
    <?php
        echo ListView::widget([
            'options' => ['class'=>'list-view'.(Yii::$app->user->isGuest?' not-cart':'')],
            'dataProvider' => $search->dataProvider,
            'layout' => "{summary}\n{items}\n{pager}",
            'itemView' => ($search instanceof OffersProducts)?'_itemOffers':'_itemLookup',
            'itemOptions' => $search->itemOptions,
        ]);
    ?>
<?php else: ?>
    Аналогов не найдено. 
<?php endif; ?>