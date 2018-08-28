<?php
    use yii\grid\GridView;
    use yii\helpers\Html;
    use yii\widgets\Pjax;
    use yii\helpers\Url;
?>

<h3>Мои заказы:</h3>
<?php Pjax::begin([
    'linkSelector'=>'.pagination a',
]); ?>
    <?=GridView::widget([
            'dataProvider' => $order->dataProvider,
            'columns' => [
                'id',
                'created_at',
                //'updated_at',
                'is_complete',
                'price_sum:text:Сумма',
                [
                    //'label'=>'Просмотр',
                    'value'=>function($data)
                    {
                        $url = Url::to(['order-item','id'=>$data['id']]);
                        /*return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                                    'title' => 'Просмотр',
                                        ]);*/
                        return Html::a('Посмотреть товары', $url);
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