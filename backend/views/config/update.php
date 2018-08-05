<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Config */

$this->title = 'Изменить: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Настройки', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="config-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
