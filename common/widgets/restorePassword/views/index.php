<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use common\assets\AjaxFormAsset;
use yii\web\View;

AjaxFormAsset::register($this);

echo $this->render('messageAfterSendMail',compact('id'));
echo $this->render('messageAfterConfirmMail',compact('id'));

Modal::begin([
    'header'=>'<h4>Восстановление пароля</h4>',
    'id'=>$id.'-modal',
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

ob_start();
require 'call_ajax_form.js';
$js = ob_get_clean();

$this->registerJS($js,View::POS_END);