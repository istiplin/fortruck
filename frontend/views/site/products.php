<?php
    use yii\widgets\DetailView;
    use yii\grid\GridView;
    use yii\widgets\Pjax;
    use yii\web\View;
    use yii\helpers\Html;
?>

<!-- информация о найденном товаре по артиклу -->
<?php if($search->productInfo):?>
    <h3>Найденный товар:</h3>
    <?= DetailView::widget([
        'model' => $search->productInfo,
        'attributes' => [
            'number:text:Артикул',
            'name:text:Наименование',
            'producerName:text:Производитель',
            'price:text:Цена',
            
            [
                'label'=>'Корзина',
                //'value'=>'',
                'value'=>function($data){
                    
                }
            ]
        ],
    ]) ?>
<?php endif; ?>

<!-- список найденных товаров -->
<?=$search->title?>
<?php Pjax::begin([
    'linkSelector'=>'.pagination a',
    'formSelector'=>'.add-to-cart',
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

<?php
    $js = <<<JS
$("#pjax-products").submit(function (e) {
    if ($(e.target).hasClass('add-to-cart'))
    {
        setTimeout(function(){
            $('#right-wrap').submit();
        },2000)
    }
})
JS;
    $this->registerJS($js,View::POS_END);
?>