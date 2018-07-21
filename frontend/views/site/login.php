<?php
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
?>
<div class="auth">
	<?php $form = ActiveForm::begin([
                'options'=>['class'=>'form-signin','name'=>'authcheck'],
                'enableClientValidation' => false,
            ])
        ?>
		<div class="logocont">
			<?=Html::img('@web/img/logo.svg')?>
			<h4 class="form-signin-heading">
				 Online-Магазин | Вход
			</h4>
		</div>

                <?php
                    $field = $form->field($login, 'username');
                    $field->template = "{input}";
                    echo $field->textInput([
                                    'placeholder' => 'Введите Логин',
                                    'class' => 'form-control',
                                    'required'=>'',
                                    'autofocus'=>'',
                                    'maxlength'=>'50', 
                            ]);
                ?>

                <?php
                    $field = $form->field($login, 'password');
                    $field->template = "{input}\n{hint}\n{error}";
                    echo $field->passwordInput([
                                    'placeholder' => 'Пароль',
                                    'class' => 'form-control',
                                    'required'=>'',
                                    'maxlength'=>'50',
                            ]);
                ?>

                <?=Html::submitButton('Поехали!',[
                                        'class'=>'btn btn-large btn-block btn-danger',
                                        'name'=>'button', 
                                        'value'=>'login',
                                    ]);
                ?>
    
                <?php /*echo Html::button('Регистрация',[
                                        'class'=>'btn btn-large btn-block btn-warning', 
                                        'name'=>'goRegistration'
                                    ]);
                 * 
                 */
                ?>
                <?=Html::a('Регистрация', 'registration/index',['class'=>'btn btn-large btn-block btn-warning'])?>
                <h5><?=Html::a('Восстановить пароль', 'restore-password/index',['class'=>'']);?></h5>
		<h5>Наш телефон: +7(926)-277-77-61</h5>
        <?php ActiveForm::end(); ?>
</div>