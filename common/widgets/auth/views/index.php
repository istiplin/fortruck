<?php
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use common\models\Config;
    use yii\bootstrap\Modal;
    use common\assets\AjaxFormAsset;
    use yii\web\View;

    AjaxFormAsset::register($this);
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

            <div class='form-group'>
                <?=Html::textInput('username', '', [
                                    'placeholder' => 'Введите Логин',
                                    'class' => 'form-control',
                                    'required'=>'',
                            ])
                ?>
            </div>
        
            <div class='form-group'>
                <?=Html::passwordInput('password', '', [
                                    'placeholder' => 'Пароль',
                                    'class' => 'form-control',
                                    'required'=>'',
                            ])
                ?>
                <div class='help-block'></div>
            </div>

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
                                            'data-toggle'=>'modal',
                                            'data-target'=>'#restore-password-modal'
                                ]);?>
            </h5>

            <h5>Наш телефон:<?=Config::value('site_phone')?></h5>

        <?php ActiveForm::end(); ?>

    </div>
<?php Modal::end(); ?>
<?php
ob_start();
require 'call_ajax_form.js';
$js = ob_get_clean();

$this->registerJS($js,View::POS_END);
