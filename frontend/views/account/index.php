<?php
    use yii\widgets\DetailView;
    use yii\helpers\Html;
?>
<?php if(Yii::$app->user->isGuest==false):?>
    Личные данные:
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
    <?= Html::a('Редактировать', ['update', 'id' => Yii::$app->user->identity->id], ['class' => 'btn btn-primary']) ?>
<?php endif; ?>