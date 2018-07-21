<?php
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\helpers\Url;
    use yii\web\View;
    use common\assets\AjaxFormAsset;
    
    AjaxFormAsset::register($this);
?>
<div class="fade in" id="restore-password" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">        
                <h4 class="modal-title" id="myModalLabel">Восстановить пароль</h4>
            </div>
            <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'action'=>'send-confirm-message-on-mail',
                    //'options' => ['class' => 'form-horizontal'],
                    'enableClientValidation' => true,
                    'enableAjaxValidation'=>true,
                    'validationUrl' => 'validate',
                ])

            ?>
            <div class="modal-body">
            <?=$form->field($model, 'email')
                    ->textInput([
                                'class' => 'form-control input',
                                'required'=>'',
                                'pattern'=>'^[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}$',
                                'maxlength'=>'60', 
                                'data-validation-pattern-message'=>'Адрес электронной почты неправильный', 
                                'data-validation-minlength-message'=>'Адрес электронной почты слишком короткий', 
                                'minlength'=>'4',
                        ])
            ?>
            </div>	
            <div class="modal-footer">
                <button class="btn btn-success" type="submit" name="button" value="registration">Отправить сообщение на почту</button>
                
                <?=Html::a('Отмена',['site/index'],['class'=>'btn btn-default'])?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<div class="popup" id="mail-send-message">
    <div class="popup_inner">
        <div class="popup-text">
            <h3>
                <div>На почту</div>
                <div class="email"></div>
                <div>отправлено сообщение.</div>
                <div>Для того чтобы восстановить пароль, необходимо подтвердить это сообщение.</div>
            </h3>
            <?=Html::a('ОК', ['/'], ['class'=>'btn btn-primary'])?>
        </div>
    </div>
</div>

<?php
    $js = <<<JS
ajax_form({
            done:function(data){
                    if (data.success==1)
                    {
                        $('#mail-send-message .email').html(data.email);
                        $('#mail-send-message').show();
                    }
                    else
                        alert('Ошибка! Данные не сохранены!');
                },
            });
JS;
    $this->registerJS($js,View::POS_END);
?>