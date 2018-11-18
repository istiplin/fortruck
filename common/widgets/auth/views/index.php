<?php
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use frontend\assets\AppAsset;
    use common\models\Config;
    use yii\bootstrap\Modal;
?>

<?php
    Modal::begin([
        'id'=>$id.'-modal',
        //'closeButton'=>false,
        'clientOptions' => ['show' => $showModalLoginForm],
    ]);
?>
    <div class="auth">
        <?php $form = ActiveForm::begin($activeFormConfig) ?>
        
            <?=Html::hiddenInput('redirectUrl', $redirectUrlAfterLogin)?>
            
            <div class="logocont">
                    <?=Html::img('@web/img/logo.svg')?>
                    <h4 class="form-signin-heading">
                             Online-Магазин | Вход
                    </h4>
            </div>

            <?php
                $field = $form->field($model, 'username');
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
                $field = $form->field($model, 'password');
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

            <?=Html::button('Регистрация', [
                                                'class'=>'btn btn-large btn-block btn-warning',
                                                'data-toggle'=>'modal',
                                                'data-target'=>'#registration-modal'
                                                            ])?>
            <h5>
                <?php echo Html::a('Восстановить пароль', '',[

                                                //'class'=>'btn btn-large btn-block btn-warning',
                                                'data-toggle'=>'modal',
                                                'data-target'=>'#restore-password-modal'
                                                            ]);?>
            </h5>

            <h5>Наш телефон:<?=Config::value('site_phone')?></h5>

        <?php ActiveForm::end(); ?>

    </div>
<?php Modal::end(); ?>