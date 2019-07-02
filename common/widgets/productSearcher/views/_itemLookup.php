<br>
<div class='number'><b><?=$model->getAttributeLabel('number')?>:</b> <?=$model->number?></div>
<div class='brand'><b><?=$model->getAttributeLabel('brandName')?>:</b> <?=$model->brandName?></div>
<div class='name'><b><?=$model->getAttributeLabel('name')?>:</b> <?=$model->name?></div>
<?=\yii\helpers\Html::a('Посмотреть аналоги', '#', ['class' => 'offers']);?>