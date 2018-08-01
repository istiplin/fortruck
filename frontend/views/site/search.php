<?php
    use yii\widgets\DetailView;
    use yii\grid\GridView;
    use yii\widgets\Pjax;
?>

<?php if($search->productInfo):?>

    <h4>Найденный товар:</h4>
    <?= DetailView::widget([
        'model' => $search->productInfo,
        'attributes' => [
            'number:text:Артикул',
            'name:text:Наименование',
            'producerName:text:Производитель',
            'price:text:Цена'
        ],
    ]) ?>
    
    <h4>Аналоги для <b><?=$search->productInfo['analogName']?></b>:</h4>
    <?php Pjax::begin([
        'linkSelector'=>'.pagination a',
        'formSelector'=>'.add-to-branch'
    ]); ?>

        <?= GridView::widget([
            'dataProvider' => $search->dataProviderForAnalog,
            'columns' => $search->columnsForAnalog,
        ]); ?>

    <?php Pjax::end(); ?>
    
<?php else: ?>
    <?php echo $this->render('products',compact('search','bag')); ?>
<?php endif; ?>
