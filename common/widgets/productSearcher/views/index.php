<?php
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\web\View;
?>
<?php
    Modal::begin([
        'id'=>$id.'-modal',
        //'closeButton'=>false,
        'size' => Modal::SIZE_LARGE,
    ]);
?>

<div class='search'>
    <?=Html::input('text', 'number', '',['placeholder' => 'Введите код или наименование запчасти',]);?>
    <?=Html::submitButton('Найти',['class'=>'lookup']);?>
</div>
<div class='res'></div>
<?php
    $selector = '#'.$id.'-modal form';
    $this->registerJS(require 'script.js',View::POS_END); 
?>
<?php Modal::end(); ?>