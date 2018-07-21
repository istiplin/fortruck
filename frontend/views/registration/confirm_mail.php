<?php
    use yii\helpers\Html;
?>
<?php if ($success): ?>
    <div class="popup">
        <div class="popup_inner">
            <div class="popup-text">
                <h3>
                    <div>Заявка отправлена.</div>
                    <div>Наш менеджер свяжется с Вами в ближайшее время.</div>
                    
                </h3>
                <?= Html::a('ОК', ['/'], ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
<?php else:?>
    <div class="popup">
        <div class="popup_inner">
            <div class="popup-text">
                <h3>
                    <div>Ошибка!!!</div>
                    <div>Заявка не отправлена.</div>
                    <div>Пройдите регистрацию заново.</div>
                </h3>
                <?= Html::a('ОК', ['/'], ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
<?php endif; ?>

