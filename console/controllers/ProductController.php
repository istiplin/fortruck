<?php

namespace console\controllers;

use \yii\console\Controller;
use console\models\LocalProducts;
use console\models\ProductsEditor;

class ProductController extends Controller
{
    
    public function actionUpdate()
    {
        $localProducts = new LocalProducts();
        $localProducts->updateAll();
    }
    
    public function actionUpdateNormNumbers()
    {
        $productsEditor = new ProductsEditor();
        $productsEditor->updateNormNumbers();
    }
    
    public function actionDeleteDublicateNumbers()
    {
        $localProducts = new ProductsEditor();
        $localProducts->deleteDublicateNumbers();
    }
}

?>
