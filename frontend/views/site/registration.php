<?php
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\helpers\Url;
    use yii\web\View;
?>
<div class="modal fade in" id="goRegistration" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
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
                    'validationUrl' => Url::to(['/registration/validate']),
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

                    <?=$form->field($registration, 'mobile')
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
                <button class="btn btn-success" type="submit" name="button" value="registration">Отправить заявку</button>
                <button type="button" class="btn btn-default" name="closeRegistration">Закрыть</button>  			
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
                <div>Для того чтобы отправить заявку, необходимо подтвердить это сообщение.</div>
            </h3>
            <button class="btn btn-primary popup-close" type="submit">OK</button>
        </div>
    </div>
</div>
<?php
    $js = <<<JS
$('[name=goRegistration]').click(function(e){
    e.preventDefault();
    $('#goRegistration').show();
})

$('[name=closeRegistration]').click(function(e){
    e.preventDefault();
    $('#goRegistration').hide();
})

$('.popup-close').click(function(e){
    $('.popup').hide();
});

$('#goRegistration form').on('beforeSubmit', function () {
    var yiiform = $(this);
    // отправляем данные на сервер
    $.ajax({
            type: yiiform.attr('method'),
            url: yiiform.attr('action'),
            data: yiiform.serializeArray()
        }
    )
    .done(function(data) {
        console.log(data);
       if(data.success) {
          // данные сохранены
          $('#goRegistration').hide();
          $('#mail-send-message .email').html(data.email);
          $('#mail-send-message').show();
        } else {
          // сервер вернул ошибку и не сохранил наши данные
          alert('Ошибка! Данные не сохранены!');
        }
    })
    .fail(function () {
         // не удалось выполнить запрос к серверу
         alert('fail');
    })

    return false; // отменяем отправку данных формы
})
JS;
    $this->registerJS($js,View::POS_END);
?>
