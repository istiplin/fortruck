<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
?>
<?php if(Yii::$app->session->hasFlash('is_success_registration')): ?>

    <?php Modal::begin([
        'id'=>'registration-confirm-message',
        'closeButton'=>false,
        'clientOptions' => ['show' => true],
    ]);
    ?>

    <div class="popup-text">
        <h3>
            <?php if(Yii::$app->session->getFlash('is_success_registration')): ?>
                <div>Заявка отправлена.</div>
                <div>Наш менеджер свяжется с Вами в ближайшее время.</div>
            <?php else: ?>
                <div>Ошибка!!!</div>
                <div>Вы уже подали заявку на регистрацию.</div>
            <?php endif; ?>

            <?=Html::button('ОК',['class'=>'btn btn-default', 'data-dismiss'=>'modal'])?>
        </h3>
    </div>

    <?php Modal::end(); ?>
<?php endif ?>
