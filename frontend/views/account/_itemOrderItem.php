<?php $this->registerCssFile('@web/css/list_view_style.css'); ?>
<div class='number'><?=$model['number']?></div>
<div class='brand'><?=$model['brandName']?></div>
<div class='name'><?=$model['name']?></div>
<div class='price'><?=$model['price']?><span class='add-info'> руб.</span></div>
<div class='count'><span class='add-info'><?=$model->getAttributeLabel('count')?>:&nbsp;</span><?=$model['count']?></div>
