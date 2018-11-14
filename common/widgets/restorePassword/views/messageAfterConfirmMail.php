<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
?>
<?php if(Yii::$app->session->hasFlash('is_success_restore_password')): ?>

    <?php Modal::begin([
        'id'=>'restore-password-confirm-message',
        'closeButton'=>false,
        'clientOptions' => ['show' => true],
    ]);
    ?>

    <div class="popup-text">
        <h3>
            <?php if(Yii::$app->session->getFlash('is_success_restore_password')): ?>
                <div>На вашу почту выслан новый пароль.</div>
            <?php else: ?>
                <div>Ошибка!!!</div>
                <div>Вам уже был выслан новый пароль.</div>
                <div>Попробуйте восстановить заново.</div> 
            <?php endif; ?>
                
            <?=Html::button('ОК',['class'=>'btn btn-default', 'data-dismiss'=>'modal'])?>
        </h3>
    </div>

    <?php Modal::end(); ?>
<?php endif ?>