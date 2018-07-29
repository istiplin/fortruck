<?php
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\helpers\Url;
    use yii\web\View;
    use common\assets\AjaxFormAsset;
    
    AjaxFormAsset::register($this);
?>
<div class="fade in" id="goRegistration" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">        
                <!-- <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-thumbs-up"></span> Регистрационная анкета</h4> -->
                <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-ok-circle"></span> Регистрационная анкета</h4>
                <!-- <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-ok-sign"></span> Регистрационная анкета</h4> -->
            </div>
            <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'action'=>'save',
                    //'options' => ['class' => 'form-horizontal'],
                    'enableClientValidation' => true,
                    'enableAjaxValidation'=>true,
                    'validationUrl' => 'validate',
                ])

            ?>
            <div class="modal-body">


                    <?=$form->field($registration, 'company_name')
                            ->textInput([
                                        'placeholder' => 'ИП Иванов',
                                        'class' => 'form-control input',
                                        'minlength'=>'3', 
                                        'data-validation-minlength-message'=>'Название компании слишком короткое',
                                        'pattern'=>'^[А-яA-z0-9\-. ]*$',
                                        'data-validation-pattern-message'=>'Название компании некорректно',
                                        'maxlength'=>'50',
                                ]);
                    ?>

                    <?=$form->field($registration, 'name')
                            ->textInput([
                                        'placeholder' => 'Иван',
                                        'class' => 'form-control',
                                        'required'=>'',
                                        'minlength'=>'1',
                                        'data-validation-minlength-message'=>'Имя слишком короткое',
                                        'maxlength'=>'40', 
                                        'pattern'=>'^[а-яА-ЯёЁa-zA-Z0-9\ ]+$', 
                                        'data-validation-pattern-message'=>'Указанное имя некорректно',
                                ]);
                    ?>

                    <?=$form->field($registration, 'phone')
                            ->textInput([
                                        'placeholder' => '+7(926)-277-77-61',
                                        'class' => 'form-control input',
                                        'required'=>'',
                                        'maxlength'=>'18',
                                        'pattern'=>'^[0-9\-+)(]*$', 
                                        'minlength'=>'7', 
                                        'data-validation-minlength-message'=>'Телефон слишком короткий',
                                        'data-validation-pattern-message'=>'Телефон указан некорректно',
                                ]);
                    ?>

                    <?=$form->field($registration, 'email')
                            ->textInput([
                                        'placeholder' => 'ivanov@mail.ru',
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
                <?=Html::submitButton('Отправить заявку', ['class'=>'btn btn-success'])?>
                <?=Html::a('Закрыть', ['/'], ['class'=>'btn btn-default'])?>
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
                <div>отправлено сообщение для подтверждения заявки.</div>
            </h3>
            <?=Html::a('ОК', ['/'], ['class'=>'btn btn-default'])?>
        </div>
    </div>
</div>
<?php
    $js = <<<JS
ajax_form({
            done:function(data){
                    if (data.success==1)
                    {
                        $('#goRegistration').hide();
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
