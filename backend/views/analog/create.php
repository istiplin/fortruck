<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Analog */

$this->title = 'Добавить аналог';
//$this->params['breadcrumbs'][] = ['label' => 'Analogs', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="analog-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
