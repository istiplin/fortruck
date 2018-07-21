<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Analog */

//$this->title = 'Update Analog: ' . $model->name;
//$this->params['breadcrumbs'][] = ['label' => 'Analogs', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
//$this->params['breadcrumbs'][] = 'Update';
?>
<div class="analog-update">

    <h1>Редактировать Аналог</h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
