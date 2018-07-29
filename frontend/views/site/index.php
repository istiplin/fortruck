<?php
    use yii\widgets\DetailView;
    use yii\helpers\Html;
?>
<?php if(Yii::$app->user->isGuest==false):?>
    Ваш профиль:
    <?= DetailView::widget([
            'model' => Yii::$app->user->identity,
            'attributes' => [
                //'id',
                'email',
                'name',
                'phone',
                'company_name',
            ],
    ])
    ?>
    <?= Html::a('Редактировать мой профиль', ['update', 'id' => Yii::$app->user->identity->id], ['class' => 'btn btn-primary']) ?>
    <br>
    <br>
    <?= Html::a('Посмотреть товары', ['search','article'=>''], ['class' => 'btn btn-primary']) ?>
<?php endif; ?>