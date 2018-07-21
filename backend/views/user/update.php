<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Редактирование: ' . $model->email;
//$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->email, 'url' => ['view', 'id' => $model->id]];
//$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
	
	<?php if($model->role->alias==='mail_confirmed'): ?>
		<?=Html::a('Зарегистрировать пользователя', 
                            ['register','id'=>$model->id], 
                            [
                                'class' => 'btn btn-success',
                                'title' => 'Пользователю приходит пароль на почту и он становится покупателем',
                            ])?>
	<?php endif; ?>
</div>
