<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use common\models\LoginForm;
use common\models\Order;

use frontend\models\productSearch\ProductSearch;
use frontend\models\productSearch\CartProductSearch;

use frontend\models\cart\Cart;

use yii\web\Response;

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
                        'actions' => ['registration-save', 'registration-validate', 'registration-confirm-url'],
                        'allow' => true,
                    ],
                    
                    [
                        'actions' => ['restore-password-send-confirm-message', 'сhange-password'],
                        'allow' => true,
                    ],
                    
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['search','cart'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['add-to-cart'],
                        'allow' => true,
                        'roles' => ['@'],
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
            
            'registration-save'=>[
                'class' => 'frontend\widgets\registrationForm\RegistrationFormAction',
                'methodName' => 'save',
                'mailConfirmUrl' => 'site/registration-confirm-url',
            ],
            'registration-validate'=>[
                'class' => 'frontend\widgets\registrationForm\RegistrationFormAction',
                'methodName' => 'validate'
            ],
            'registration-confirm-url'=>[
                'class' => 'frontend\widgets\registrationForm\RegistrationFormAction',
                'methodName' => 'confirmRegistration',
                'redirectUrl' => ['site/login']
            ],
            
            'restore-password-send-confirm-message'=>[
                'class' => 'frontend\widgets\restorePasswordForm\RestorePasswordFormAction',
                'methodName' => 'sendConfirmMessage',
                'mailConfirmUrl' => 'site/сhange-password',
            ],
            'сhange-password'=>[
                'class' => 'frontend\widgets\restorePasswordForm\RestorePasswordFormAction',
                'methodName' => 'сhangePassword',
                'redirectUrl' => ['site/login']
            ]
        ];
    }
    
    public function actionIndex()
    {
        return $this->redirect(['account/index']);
    }
    
    public function actionAddToCart($id,$count)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        if (ctype_digit($count))
        {
            $cart = Cart::initial();
            $cart->update($id,$count);

            if ($count>0)
                $message = 'Товар обновлен в корзине';
            else
                $message = 'Товар удален из корзины';

            return [
                'status' => 'success',
                'moneySumm'=>$cart->priceSum,
                'qty'=>$cart->countSum,
                'message' => $message,
            ];
        }
        
        return [
            'status' => 'error',
            'message' => 'Задано неверное количество товаров',
        ];
    }
    
    public function actionSearch()
    {
        $text='';
        if (strlen(Yii::$app->request->get('text'))>0)
            $text = Yii::$app->request->get('text');
        else
            return $this->render('products',compact('search'));
        
            
        $this->view->params['text'] = $text;
        $search = ProductSearch::initial($text);

        if(Yii::$app->request->post('cart')!==null)
        {
            $id = Yii::$app->request->post('cart')['id'];
            $count = Yii::$app->request->post('cart')['count'];
            $search->cart->update($id,$count);
            $this->refresh();
        }
        
        return $this->render('products',compact('search'));
    }
    
    public function actionCart()
    {   
        $search = new CartProductSearch;
        $order = new Order;
        
        //обновление корзины
        if(Yii::$app->request->post('cart')!==null)
        {
            $id = Yii::$app->request->post('cart')['id'];
            $count = Yii::$app->request->post('cart')['count'];
            $search->cart->update($id,$count);
            $this->refresh();
        }
        
        //оформление заказа
        if (Yii::$app->request->post('form_order')!==null)
        {
            $order->form(Cart::initial());
            return $this->refresh();
        }
        
        return $this->render('cart',compact('search'));
    }
    
    //переводит на страницу авторизации
    public function actionLogin()
    {
        $this->layout = 'main';
                
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