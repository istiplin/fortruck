<?php
    use yii\widgets\DetailView;
    use yii\grid\GridView;
    use yii\widgets\Pjax;
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
            'price:text:Цена'
        ],
    ]) ?>
<?php endif; ?>

<!-- список найденных товаров -->
<?=$search->title?>
<?php Pjax::begin([
    'linkSelector'=>'.pagination a',
    'formSelector'=>'.add-to-bag'
]); ?>

    <?=GridView::widget([
        'dataProvider' => $search->dataProvider,
        'columns' => $search->columns,
        ]);
    ?>

<?php Pjax::end(); ?>