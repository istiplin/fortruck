<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use common\assets\AjaxFormAsset;
use yii\web\View;

AjaxFormAsset::register($this);

echo $this->render('messageAfterSendMail');
echo $this->render('messageAfterConfirmMail');

Modal::begin([
    'header'=>'<h4>Восстановление пароля</h4>',
    'id'=>'restore-password-modal',
    'closeButton'=>false,
]);

$form = ActiveForm::begin($activeFormConfig);

echo $form->field($model, 'email')->textInput([
                        'class' => 'form-control input',
                        'required'=>'',
                ])
?>
    <div class="form-group">
        <div class="text-right">
            <?=Html::submitButton('Восстановить пароль', ['class'=>'btn btn-success'])?>
            <?=Html::button('Закрыть',[
                                            'class'=>'btn btn-default',
                                            'data-dismiss'=>'modal']); ?>
        </div>
    </div>
<?php
ActiveForm::end();
Modal::end();

$this->registerJsVar('restore_password_id',$activeFormConfig['id'],View::POS_END);
ob_start();
require 'call_ajax_form.js';
$js = ob_get_clean();

$this->registerJS($js,View::POS_END);