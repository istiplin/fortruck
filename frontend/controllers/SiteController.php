<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

use common\models\Order;
use common\models\Product;

use frontend\models\Products;
use frontend\models\productSearch\CartProductSearch;

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
    
    public function actionSearch($number=null,$brandName=null)
    {
        if ($number===null)
            return $this->renderContent('');
        
        $this->view->params['number'] = $number;
        
        $search = Products::initial($number,$brandName,Config::value('is_remote'));
        if ($search instanceof \frontend\models\LookupProducts)
            return $this->render('lookup',compact('search'));
        if ($search instanceof \frontend\models\OffersProducts)
            return $this->render('offers',compact('search'));
    }
    
    public function actionCart()
    {   
        //оформление заказа
        if (Yii::$app->request->get('form_order')!==null)
        {
            $order = new Order;
            $orderId = $order->form();
            if ($orderId)
            {
                Yii::$app->session->setFlash('is_checkout',true);
                return $this->redirect(['account/order-item','id'=>$orderId]);
            }
        }
        
        $search = new CartProductSearch;
        if (Yii::$app->request->isAjax)
            return $this->renderPartial('cart',compact('search'));
        else
            return $this->render('cart',compact('search'));
    }

    public function actionAddToCart()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $product = $_GET['product'];
        $count = $_GET['count'];

        return \frontend\models\cart\Cart::initial()->update($product,$count);
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