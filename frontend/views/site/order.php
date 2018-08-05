<?php
    use yii\grid\GridView;
    use yii\widgets\Pjax;
?>

<?php Pjax::begin([
    'linkSelector'=>'.pagination a',
    'formSelector'=>'.add-to-bag'
]); ?>
    <?=$search->title?>
    <?=GridView::widget([
        'dataProvider' => $search->dataProvider,
        'columns' => $search->columns
    ])
    ?>
<?php Pjax::end(); ?>