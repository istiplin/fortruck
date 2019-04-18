<?php
    use yii\grid\GridView;
    use yii\widgets\ListView;
?>
<?php if ($search->dataProvider->totalCount): ?>

    <?=$search->title?>

    <?php
        $header = "<div class='row-header'>
<div class='number'>{$search->getAttributeLabel('number')}</div>
<div class='brand'>{$search->getAttributeLabel('brandName')}</div>
<div class='name'>{$search->getAttributeLabel('name')}</div>
</div>";
        echo ListView::widget([
            'dataProvider' => $search->dataProvider,
            'layout' => "{summary}$header\n{items}\n{pager}",
            'itemView' => '_itemLookup',
            'itemOptions' => $search->itemOptions,
        ]);
    ?>
<?php else: ?>
    Ничего не найдено.
<?php endif; ?>