<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use common\models\LoginForm;
use common\models\User;
use common\models\Order;

use frontend\models\RegistrationForm;
use frontend\models\productSearch\ProductSearch;
use frontend\models\productSearch\BagProductSearch;
use frontend\models\productSearch\OrderProductSearch;
use frontend\models\bag\GuestBag;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public $layout = 'header';
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'logout'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function($rule, $action) {
                            return Yii::$app->user->identity->id==Yii::$app->request->get('id');   
                        }
                    ],

                    [
                        'actions' => ['index'],
                        'allow' => true,
                        //'roles' => ['@'],
                    ],
                    [
                        'actions' => ['search','bag'],
                        'allow' => true,
                        //'roles' => ['@'],
                    ],
                    [
                        'actions' => ['order'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function($rule, $action) {
                            return Yii::$app->user->identity->id==Order::findOne(Yii::$app->request->get('id'))->user_id;   
                        }
                    ],

                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest)
            return $this->redirect(['search']);
        return $this->render('index');
    }
    
    public function actionSearch()
    {
        $text='';
        if (strlen(Yii::$app->request->get('text'))>0)
            $text = Yii::$app->request->get('text');

        $this->view->params['text'] = $text;
        $search = ProductSearch::initial($text);

        if(Yii::$app->request->isPjax && Yii::$app->request->post('bag')!==null)
        {
            $id = Yii::$app->request->post('bag')['id'];
            $count = Yii::$app->request->post('bag')['count'];
            $search->bag->update($id,$count);
        }
        
        return $this->render('products',compact('search'));
    }
    
    public function actionBag()
    {   
        $search = new BagProductSearch;
        $order = new Order;
        
        //обновление корзины
        if(Yii::$app->request->isPjax && Yii::$app->request->post('bag')!==null)
        {
            $id = Yii::$app->request->post('bag')['id'];
            $count = Yii::$app->request->post('bag')['count'];
            $search->bag->update($id,$count);
        }
        
        //формирование заказа
        if (Yii::$app->request->post('form_order')!==null)
        {
            $order->form($search->bag);
            return $this->refresh();
        }
        
        return $this->render('bag',compact('search','order'));
    }
    
    public function actionOrder($id)
    {
        $search = new OrderProductSearch($id);
        return $this->render('order',compact('search'));
    }
    
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('_form', [
            'model' => $model,
        ]);
    }
    
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    //переводит на страницу авторизации
    public function actionLogin()
    {
        //$this->layout = 'main';
                
        if (!Yii::$app->user->isGuest)
            return $this->goHome();
        
        $login = new LoginForm;
        //если успешно авторизовались
        if ($login->load(Yii::$app->request->post()) && $login->login())
        {
            return $this->goBack();
        }
        //иначе
        else
        {
            //выводим ошибку
            //избавляемся от всплывающих сообщений браузера
            if (Yii::$app->session->hasFlash('errors')===false)
            {
                //делаем обновление после сохранения сообщений об ошибках и значений атрибутов
                
                Yii::$app->session->setFlash('errors',$login->errors);
                $login->password='';
                Yii::$app->session->setFlash('attributes',$login->attributes);
                return $this->refresh();
            }
            else
            {
                //выводим сообщения об ошибках и значения атрибутов
                
                $login->setAttributes(Yii::$app->session->getFlash('attributes'));
                $login->addErrors(Yii::$app->session->getFlash('errors'));
                return $this->render('login',compact('login'));
            }
        }

    }
    
    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}