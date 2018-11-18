<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
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
                        'actions' => [
                                        'error','auth','restore-password','registration',
                                        'index','search','request-price',
                                    ],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['add-to-cart','cart'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],

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
            ],
            'request-price'=>[
                'class'=>'common\widgets\requestPrice\RequestPriceAction'
            ]
        ];
    }
    
    public function actionIndex()
    {
        return $this->redirect(['search']);
    }
    
    public function actionSearch($text=null)
    {
        $text = trim($text);
        if (strlen($text)==0)
            return $this->render('products');
        
        $this->view->params['text'] = $text;
        $search = ProductSearch::initial($text);
        return $this->render('products',compact('search'));
    }
    
    public function actionCart()
    {   
        //оформление заказа
        if (Yii::$app->request->post('form_order')!==null)
        {
            $order = new Order;
            $order->form(Cart::initial());
            return $this->refresh();
        }
        
        $search = new CartProductSearch;
        return $this->render('cart',compact('search'));
    }

    public function actionAddToCart($id,$count)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $cart = Cart::initial();
        return $cart->update($id,$count);
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