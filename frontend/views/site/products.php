<?php
    use yii\grid\GridView;
    use yii\widgets\Pjax;
    use yii\helpers\Html;
?>
<?php if (strlen($search->search)):?>
    <h4>Результаты поиска по запросу "<b><?=$search->search?></b>"</h4>
<?php endif; ?>
<?php Pjax::begin([
    'linkSelector'=>'.pagination a',
    'formSelector'=>'.add-to-branch'
]); ?>
    <?=GridView::widget([
        'dataProvider' => $search->dataProviderForProducts,
        'columns' => $search->columnsForProducts,
    ]);
?>
<?php Pjax::end(); ?>