<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Producer */

$this->title = 'Добавить производителя';
//$this->params['breadcrumbs'][] = ['label' => 'Companies', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="producer-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
