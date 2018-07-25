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
            [
                'label'=>'Артикул',
                'value'=>function($data){
                    return $data['number'];
                }
            ],
            [
                'label'=>'Наименование',
                'value'=>function($data){
                    return $data['name'];
                }
            ],
            [
                'label'=>'Производитель',
                'value'=>function($data){
                    return $data['producerName'];
                }
            ],
            [
                'label'=>'Цена',
                'value'=>function($data){
                    return '-';
                }
            ]
        ],
    ]) ?>
    <h4>Аналоги для <b><?=$search->productInfo['analogName']?></b>:</h4>
    
    <?php Pjax::begin(); ?>
            
    <?= GridView::widget([
        'dataProvider' => $search->dataProvider,
        'columns' => [

            'number',
            'producerName',
            [
                'attribute'=>'name',
                'value'=> function($data){
                    if (strlen($data->name))
                        return $data->name;
                    else
                        return $data->analog->name;
                },
                'filter'=>'',
            ],
        ],
    ]); ?>
            
    <?php Pjax::end(); ?>
<?php else: ?>
    Ничего не найдено
<?php endif; ?>
