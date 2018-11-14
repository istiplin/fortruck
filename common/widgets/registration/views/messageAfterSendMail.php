<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;

Modal::begin([
    'id'=>'mail-send-message',
    'closeButton'=>false
]);
?>
    <div class="popup-text">
        <h3>
            <div>На почту</div>
            <div class="email"></div>
            <div>отправлено сообщение для подтверждения заявки.</div>
        </h3>
        <?=Html::button('ОК', ['class'=>'btn btn-default', 'data-dismiss'=>'modal'])?>
    </div>
<?php Modal::end(); ?>