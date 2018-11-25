<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use common\assets\AjaxFormAsset;
use yii\web\View;

AjaxFormAsset::register($this);

echo $this->render('messageAfterSendMail',compact('id'));

Modal::begin([
    //'header'=>'<h4>Запрос цены на товар</h4>',
    'id'=>$id.'-modal',
    'closeButton'=>false,
]);
?>
Для определения цены, Вам необходимо пройти 
<?=Html::a('авторизацию','',['data-toggle'=>'modal','data-target'=>'#auth-modal'])?> 
или отправить заявку:<br><br>
<?php
$form = ActiveForm::begin($activeFormConfig)

?>
    <?=$form->field($model, 'number')
            ->textInput([
                        'class' => 'form-control',
                        'readonly' => true
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

ob_start();
require 'call_ajax_form.js';
$js = ob_get_clean();

$this->registerJS($js,View::POS_END);