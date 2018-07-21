<?php

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use common\models\Role;
    /* @var $this yii\web\View */
    /* @var $model common\models\User */
    /* @var $form yii\widgets\ActiveForm */
    
    use yii\web\View;
    //use frontend\assets\AjaxFormAsset;
    use common\assets\AjaxFormAsset;

    AjaxFormAsset::register($this);
?>

<div class="user-form">

    <?php $form = ActiveForm::begin([
        'action'=>['save', 'id' => $model->id],
        //'enableClientValidation' => false,
    ]); 
    ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true,'disabled'=>true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'company_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'role_id')->dropDownList($model->roles); ?>

    <div class="form-group">
        <?= Html::button('Назад', ['class' => 'btn btn-success', 'onClick'=>'history.back()']) ?>
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
    $js = <<<JS
ajax_form({
        done:function(data){
            if (data.success)
                history.back();
            else
                alert('Ошибка при сохранении данных!');
        }
    });
JS;
    $this->registerJS($js,View::POS_END);
?>