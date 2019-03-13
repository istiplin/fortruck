<?php

namespace console\controllers;

use \yii\console\Controller;
use console\models\Integration;
use console\models\LocalProducts;

class IntegrationController extends Controller
{
    
    public function actionIndex()
    {
        $localProducts = new LocalProducts();
    }
}

?>
