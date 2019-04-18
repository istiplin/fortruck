<?php

namespace console\controllers;

use \yii\console\Controller;
use console\models\Integration;
use console\models\LocalProducts;

class IntegrationController extends Controller
{
    
    public function actionIndex()
    {
		//ini_set('max_execution_time', 10);
        $localProducts = new LocalProducts();
        $localProducts->updateAll();
    }
}

?>
