<?php
    use yii\grid\GridView;
    use yii\widgets\ListView;
    use yii\widgets\Pjax;
?>
<?php if ($search->dataProvider->totalCount): ?>

    <?=$search->title?>

    <?php

        echo ListView::widget([
            'dataProvider' => $search->dataProvider,
            'layout' => "{summary}\n{items}\n{pager}",
            'itemView' => '_itemLookup',
            'itemOptions' => $search->itemOptions,
        ]);

    ?>
<?php else: ?>
    Ничего не найдено.
<?php endif; ?>