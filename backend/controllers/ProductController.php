<?php

namespace backend\controllers;

use Yii;
use common\models\Product;

use backend\models\ProductSearch;
use backend\controllers\CRUDController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\db\Query;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends CRUDController
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
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();
        $model -> is_visible = 1;
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //return $this->redirect(['index']);
            return $this->redirect(['view','id'=>$model->id]);
        }

        //print_r($model->errors);
        
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //return $this->redirect(['index']);
            return $this->redirect(['view','id'=>$model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    /*
    public function actionProducerList($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            //$out['results'] = Producer::find()->limit(2)->select('name,id')->indexBy('id')->asArray()->column();
            //$out['results'] = Producer::find()->limit(2)->select('id,name as text')->all();
        $query = new Query;
        $query->select('id, name AS text')
            ->from('product')
            ->where(['like', 'name', $q])
            ->limit(10);
        $command = $query->createCommand();
        $data = $command->queryAll();
        $out['results'] = array_values($data);
        }
        return $out;
    }
     * 
     */
    
    public function actionOriginalNumberList($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query;
            $query->select('id, number AS text')
                ->from('product')
                ->andWhere(['like', 'number', $q])
                ->andWhere('original_id=id')
                ->limit(10);
            $command = $query->createCommand();
            $data = $command->queryAll();
            
            array_unshift($data,['id'=>0,'text'=>'ДОБАВЛЯЕМЫЙ ТОВАР ИМЕЕТ ОРИГИНАЛЬНЫЙ НОМЕР']);
            $out['results'] = array_values($data);
        }
        return $out;
    }
    
    public function actionNumberList($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query;
            $query->select('id, number AS text')
                ->from('product')
                ->andWhere(['like', 'number', $q])
                ->limit(10);
            $command = $query->createCommand();
            $data = $command->queryAll();
            
            //array_unshift($data,['id'=>0,'text'=>'ДОБАВЛЯЕМЫЙ ТОВАР ИМЕЕТ ОРИГИНАЛЬНЫЙ НОМЕР']);
            $out['results'] = array_values($data);
        }
        return $out;
    }
    
    public function actionLoadXml()
    {
        $doc = new \DOMDocument();
        $doc->load($_FILES['filename']['tmp_name']);
        $records = $doc->getElementsByTagName('data-set')->item(0)->getElementsByTagName('record');
        foreach($records as $record)
        {
            $number = $record->getElementsByTagName('number')->item(0)->nodeValue;
            
            if (($product = Product::findByNumber($number)) === null) //print_r($product);
                $product = new Product();
            
            $product->number = $number;
            $product->originalNumber = $record->getElementsByTagName('originalNumber')->item(0)->nodeValue;
            $product->producer_name = $record->getElementsByTagName('producer_name')->item(0)->nodeValue;
            $product->name = $record->getElementsByTagName('name')->item(0)->nodeValue;

            $product->save();
        }
        return $this->redirect(['index']);
    }
}
