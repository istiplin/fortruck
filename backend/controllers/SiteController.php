<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
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
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->isAdmin();
                        },
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post','get'],
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

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    //выводит меню для редакторования товаров
    public function actionProductEditorMenu()
    {
        return $this->render('product_menu');
    }
    
    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        
        
        if (!Yii::$app->user->isGuest)
            return $this->goHome();

        $model = new LoginForm;
        //если успешно авторизовались
        if ($model->load(Yii::$app->request->post()) && $model->login()) 
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
                
                Yii::$app->session->setFlash('errors',$model->errors);
                $model->password='';
                Yii::$app->session->setFlash('attributes',$model->attributes);
                return $this->refresh();
            }
            else
            {
                //выводим сообщения об ошибках и значения атрибутов
                
                $model->setAttributes(Yii::$app->session->getFlash('attributes'));
                $model->addErrors(Yii::$app->session->getFlash('errors'));
                return $this->render('login',compact('model'));
            }
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
