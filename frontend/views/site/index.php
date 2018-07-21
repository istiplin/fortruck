<?php
    use yii\widgets\DetailView;
    use yii\helpers\Html;
?>
Ваш профиль:
<?= DetailView::widget([
        'model' => Yii::$app->user->identity,
        'attributes' => [
            //'id',
            'email',
            'name',
            'mobile',
            'company_name',
        ],
])
?>
<?= Html::a('Редактировать мой профиль', ['update', 'id' => Yii::$app->user->identity->id], ['class' => 'btn btn-primary']) ?>