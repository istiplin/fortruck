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
    <?php if($search->productInfo):?>
        <h3>Найденный товар:</h3>
        <?= DetailView::widget([
            'model' => $search->productInfo,
            'attributes' => [
                'number:text:Артикул',
                'name:text:Наименование',
                'producer_name:text:Производитель',
                [
                    'label'=>'Цена',
                    'value'=>function($data)
                    {
                        return sprintf("%01.2f", $data['price']);
                    }
                ],
                [
                    'label'=>'В корзине',
                    'value'=>function($data){
                        return Cart::initial()->getCount($data['id']);
                    }
                ],
                [
                    'label'=>'Заказ',
                    'value'=>function($data){
                        $count = Cart::initial()->getCount($data['id']);

                        return Html::beginForm('', 'post', ['class' => 'add-to-cart']).
                                    Html::hiddenInput('cart[id]', $data['id']).
                                    //Html::submitButton('-').
                                    Html::input('text', 'cart[count]', $count,['size'=>1,'class'=>'cart-count']).
                                    //Html::submitButton('+').
                                    Html::submitButton('',['class'=>'cart-button']).
                                Html::endForm();
                    },
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