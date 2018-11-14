<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use common\models\LoginForm;
use common\models\Order;
use common\models\Product;

use frontend\models\productSearch\ProductSearch;
use frontend\models\productSearch\CartProductSearch;

use frontend\models\cart\Cart;

use yii\web\Response;
use common\models\Config;

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
                        'actions' => ['error','auth','restore-password','registration'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['request-price'],
                        'allow' => true,
                    ],
                    
                    [
                        'actions' => ['index'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['search'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['add-to-cart','cart'],
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
                'class' => 'yii\web\ErrorAction'
            ],
            'registration'=>[
                'class'=>'common\widgets\registration\RegistrationAction'
            ],
            'restore-password'=>[
                'class'=>'common\widgets\restorePassword\RestorePasswordAction'
            ],
            'auth'=>[
                'class'=>'common\widgets\auth\AuthAction'
            ]
        ];
    }
    
    public function actionIndex()
    {
        return $this->redirect(['search']);
    }
    
    public function actionSearch()
    {
        $text='';
        if (strlen(Yii::$app->request->get('text'))>0)
            $text = trim(Yii::$app->request->get('text'));
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
            $order = new Order;
            $order->form(Cart::initial());
            return $this->refresh();
        }
        
        return $this->render('cart',compact('search'));
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
    
    public function actionRequestPrice($id)
    {
        $product = Product::findOne($id);
        Yii::$app->mailer->compose('requestPrice',compact('product'))
                    ->setTo(Config::value('site_email'))
                    ->setSubject('Запрос цены на товар')
                    ->send();
    }
}