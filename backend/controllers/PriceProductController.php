<?php

namespace backend\controllers;

use Yii;
use backend\models\ProductSearch;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class PriceProductController extends ProductController
{
    
    public function actionMenu()
    {
        return $this->render('menu');
    }
    
    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->searchPrice(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}