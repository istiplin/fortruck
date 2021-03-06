<?php

namespace backend\controllers;

use Yii;
use common\models\OrderItem;
use common\models\Product;
use backend\models\OrderItemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use frontend\models\Product as CustProduct;

/**
 * OrderItemController implements the CRUD actions for OrderItem model.
 */
class OrderItemController extends CRUDController
{
    public function actions()
    {
        return [
            'product-searcher'=>[
                'class'=>'common\widgets\productSearcher\ProductSearcherAction'
            ],
        ];
    }
    /**
     * Lists all OrderItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrderItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrderItem model.
     * @param integer $order_id
     * @param integer $product_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($order_id, $product_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($order_id, $product_id),
        ]);
    }

    /**
     * Creates a new OrderItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($order_id)
    {
        $model = new OrderItem();

        $model->order_id = $order_id;
        
        if ($model->load(Yii::$app->request->post()))
        {
            $normNumber = Yii::$app->request->post('normNumber');
            $brandName = Yii::$app->request->post('brandName');
            $model->product_id = Product::getInfoByNumberAndBrandName($normNumber,$brandName)->id;
            if ($model->save())
                return $this->redirect(['index','order_id'=>$order_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing OrderItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $order_id
     * @param integer $product_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($order_id, $product_id)
    {
        $model = $this->findModel($order_id, $product_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index','order_id'=>$order_id]);
        }
        
        $product = $model->product;
        $custProduct = new CustProduct([
                                        'number'=>$product->number,
                                        'name'=>$product->name,
                                        'brandName'=>$product->brandName,
                                        'price'=>$product->price,
                                    ]);
        return $this->render('update', [
            'model' => $model,
            'custProduct' => $custProduct
        ]);
    }

    /**
     * Deletes an existing OrderItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $order_id
     * @param integer $product_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($order_id, $product_id)
    {
        $this->findModel($order_id, $product_id)->delete();

        return $this->redirect(['index','order_id'=>$order_id]);
    }

    /**
     * Finds the OrderItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $order_id
     * @param integer $product_id
     * @return OrderItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($order_id, $product_id)
    {
        if (($model = OrderItem::findOne(['order_id' => $order_id, 'product_id' => $product_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
