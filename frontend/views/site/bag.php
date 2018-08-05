<?php
    use yii\grid\GridView;
    use frontend\model\BagModel;
    use yii\helpers\Html;
    use yii\helpers\Url;
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
    
<?php if(!Yii::$app->user->isGuest):?>
    <h3>Мои заказы:</h3>
    <?php Pjax::begin([
        'linkSelector'=>'#p1 .pagination a',
    ]); ?>
        <?=GridView::widget([
                'dataProvider' => $order->dataProvider,
                'columns' => [
                    'id',
                    'created_at',
                    //'updated_at',
                    'is_complete',
                    [
                        'label'=>'Просмотр',
                        'value'=>function($data)
                        {
                            $url = Url::to(['order','id'=>$data['id']]);
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                                        'title' => 'Просмотр',
                                            ]);
                        },
                        'format'=>'raw',
                        
                        /*
                        'class' => 'yii\grid\ActionColumn',
                        'buttons'=>[
                            'view'=>function($url,$model)
                            {
                                $url = Url::to(['order','id'=>$model['id']]);
                                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                                            'title' => 'Просмотр',
                                                ]);
                            }
                        ],
                        'template'=>'{view}',
                         * 
                         */
                    ]
                ]
            ])
        ?>
    <?php Pjax::end(); ?>
<?php endif; ?>