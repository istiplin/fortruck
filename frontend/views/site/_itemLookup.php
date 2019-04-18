<?php $this->registerCssFile('@web/css/list_view_style.css'); ?>
<div class='number'><?=\yii\helpers\Html::a($model->number, ['site/search', 'number' => $model->number, 'brandName' => $model->brandName], ['title' => 'Посмотреть аналоги']);?></div>
<div class='brand'><?=$model->brandName?></div>
<div class='name'><?=$model->name?></div>