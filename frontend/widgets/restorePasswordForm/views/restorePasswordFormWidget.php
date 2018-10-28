<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use common\assets\AjaxFormAsset;
use yii\web\View;

AjaxFormAsset::register($this);

Modal::begin([
    'id'=>'restore-password-mail-send-message',
    'closeButton'=>false
]);
?>

        <div class="popup-text">
            <h3>
                <div>На почту</div>
                <div class="email"></div>
                <div>отправлено сообщение.</div>
                <div>Для того чтобы восстановить пароль, необходимо подтвердить это сообщение.</div>
            </h3>
            <?=Html::button('ОК', [
                                    'class'=>'btn btn-default',
                                    'data-dismiss'=>'modal'])?>
        </div>
    
<?php
Modal::end();

if(Yii::$app->session->hasFlash('is_success_restore_password'))
{
    Modal::begin([
        'id'=>'restore-password-confirm-message',
        'closeButton'=>false,
        'clientOptions' => ['show' => true],
    ]);
    ?>
    
    <div class="popup-text">
        <h3>
            <?php if(Yii::$app->session->getFlash('is_success_restore_password')): ?>
            
                <div>На вашу почту выслан новый пароль.</div>
                
            <?php else: ?>
                
                <div>Ошибка!!!</div>
                <div>Вам уже был выслан новый пароль.</div>
                <div>Попробуйте восстановить заново.</div>
                    
            <?php endif; ?>

            <?=Html::button('ОК',[
                                            'class'=>'btn btn-default',
                                            'data-dismiss'=>'modal']);?>
        </h3>
    </div>

    <?php
    Modal::end();
}

Modal::begin([
    'header'=>'<h4>Восстановление пароля</h4>',
    'id'=>'restore-password-modal',
    'closeButton'=>false,
]);

$form = ActiveForm::begin($activeFormConfig)

?>
 
    <?=$form->field($model, 'email')->textInput([
                        'class' => 'form-control input',
                        'required'=>'',
                        'pattern'=>'^[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}$',
                        'maxlength'=>'60', 
                        'data-validation-pattern-message'=>'Адрес электронной почты неправильный', 
                        'data-validation-minlength-message'=>'Адрес электронной почты слишком короткий', 
                        'minlength'=>'4',
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

$js = <<<JS
ajax_form({
            done:function(data){
                    if (data.success==1)
                    {
                        $('#restore-password-modal').modal('hide');
                        $('#restore-password-mail-send-message .email').html(data.email);
                        $('#restore-password-mail-send-message').modal('show');
                        
                        $('#{$activeFormConfig['id']} .form-control').val('');
                        $('#{$activeFormConfig['id']}').yiiActiveForm('resetForm');
                    }
                    else if (data.success==0)
                    {
                        $('#{$activeFormConfig['id']}').yiiActiveForm('updateMessages',data.messages);
                    }
                },
             selector:'#{$activeFormConfig['id']}'
        });
JS;
$this->registerJS($js,View::POS_END);