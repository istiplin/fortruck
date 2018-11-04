<?php
    use yii\helpers\Html;
    use yii\helpers\Url;

    $baseUrl = Yii::$app->request->baseUrl;
    Yii::$app->request->baseUrl = '/admin';
    $link = Yii::$app->urlManager->createAbsoluteUrl(['product/update', 'id'=>$product->id]);
    
    Yii::$app->request->baseUrl = $baseUrl;

?>

Один из пользователей запросил цену на товар с номером <?=Html::a($product->number,$link)?>
