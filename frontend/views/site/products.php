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
    <?php if($search->productInfo AND $search->productInfo['is_visible']):?>
        <h3>Найденный товар:</h3>
        <?= DetailView::widget([
            'model' => $search->productInfo,
            'attributes' => [
                'number:text:Артикул',
                'name:text:Наименование',
                'producer_name:text:Производитель',
                [
                    'label'=>'Цена',
                    'value'=>function($data){
                        if ($data['price'] AND $data['count'])
                        {
                            if (Yii::$app->user->isGuest)
                                return Html::a('Посмотреть цену','',['class'=>'request-price-button','data-number'=>$data['number'],'data-toggle'=>'modal','data-target'=>'#request-price-modal']);
                            else
                                return sprintf("%01.2f", $data['price']);
                        }
                        else
                            return '-';
                    },
                    'format'=>'raw'
                ],
                [
                    'label'=>'Количество',
                    'value'=>function($data)
                    {
                        if($data['price'] AND $data['count'])
                            return $data['count'];
                        else
                            return 'Нет в наличии';
                    },
                    'format'=>'raw',
                ],
                [
                    'label'=>'В корзине',
                    'value'=>function($data){
                        if ($data['price'] AND $data['count'])
                            return "<span class='cart-count-value' data-id={$data['id']}>".Cart::initial()->getCount($data['id'])."</span>";
                        else
                            return '-';
                    },
                    'visible'=>!Yii::$app->user->isGuest,
                    'format'=>'raw'
                ],
                [
                    'label'=>'Заказ',
                    'value'=>function($data){
                        if ($data['price'] AND $data['count'])
                        {
                            $count = Cart::initial()->getCount($data['id']);
                            return "<div class='add-to-cart'>".
                                        Html::button('-', ['class'=>'minus-button']).
                                        Html::input('text', 'cart[count]', $count,['size'=>1,'class'=>'cart-count','data-id'=>$data['id']]).
                                        Html::button('+', ['class'=>'plus-button']).
                                        Html::submitButton('',['class'=>'cart-button']).
                                    "</div>";
                        }
                        else
                            return '-';
                    },
                    'visible'=>!Yii::$app->user->isGuest,
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