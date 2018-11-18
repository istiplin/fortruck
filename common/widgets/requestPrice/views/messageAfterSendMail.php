<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;

Modal::begin([
    'id'=>$id.'-message-after-send-mail',
    'closeButton'=>false
]);
?>
    <div class="popup-text">
        <h3>
            <div>Заявка отправлена!</div>
            <div>Наш менеджер свяжется с Вами в ближайшее время</div>
        </h3>
        <?=Html::button('ОК', ['class'=>'btn btn-default', 'data-dismiss'=>'modal'])?>
    </div>
<?php Modal::end(); ?>