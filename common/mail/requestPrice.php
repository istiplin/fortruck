<?php
    use yii\helpers\Html;
    use yii\helpers\Url;

    //$baseUrl = Yii::$app->request->baseUrl;
    //Yii::$app->request->baseUrl = '/admin';
    //$link = Yii::$app->urlManager->createAbsoluteUrl(['product/update', 'id'=>$product->id]);
    //Yii::$app->request->baseUrl = $baseUrl;

?>

Один из пользователей запросил цену на товар:<br>
<br>
Запрашиваемый артикул: <?=$request->number?><br>
Бренд: <?=$request->brandName?><br>
Имя: <?=$request->name?><br>
Телефон: <?=$request->phone?><br>
Email: <?=$request->email?><br>
