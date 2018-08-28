<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Role;

/* @var $this yii\web\View */
/* @var $model common\models\User */

?>
<div class="user-view">
    <h1>Просмотр</h1>
    <p>
        <?= Html::button('Назад', ['class' => 'btn btn-success', 'onClick'=>'history.back()']) ?>
        <!--
        <?= Html::a('Назад', ['index'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        -->
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'email',
            //'password',
            //'auth_key',
            //'operation_key',
            'name',
            'phone',
            'company_name',
            'registration_data',
            [
                'attribute'=>'role_id',
                'value'=> function($data){
                    return $data->role->name;
                }
            ],
        ],
    ]) ?>

</div>
