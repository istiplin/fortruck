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
    'header'=>'<h4><span class="glyphicon glyphicon-ok-circle"></span> Регистрационная анкета</h4>',
    'id'=>'registration-modal',
    'closeButton'=>false,
]);

$form = ActiveForm::begin($activeFormConfig)

?>

    <?=$form->field($model, 'company_name')
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

    <?=$form->field($model, 'name')
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

    <?=$form->field($model, 'phone')
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

    <?=$form->field($model, 'email')
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
    <div class="form-group">
        <div class="text-right">
            <?=Html::submitButton('Отправить заявку', ['class'=>'btn btn-success'])?>
            <?=Html::button('Закрыть',[
                                            'class'=>'btn btn-default',
                                            'data-dismiss'=>'modal']); ?>
        </div>
    </div>
<?php
ActiveForm::end();
Modal::end();

$this->registerJsVar('registration_id',$activeFormConfig['id'],View::POS_END);
ob_start();
require 'call_ajax_form.js';
$js = ob_get_clean();

$this->registerJS($js,View::POS_END);