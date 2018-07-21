<?php
    use yii\helpers\Html;
?>
<?php if ($success): ?>
    <div class="popup">
        <div class="popup_inner">
            <div class="popup-text">
                <h3>
                    <div>На вашу почту выслан новый пароль.</div>
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
                    <div>Ошибка при восстановлении пароля!!!</div>
                </h3>
                <?= Html::a('ОК', ['/'], ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
<?php endif; ?>