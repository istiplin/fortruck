<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use common\models\LoginForm;
use common\models\User;

use frontend\models\RegistrationForm;
use frontend\models\SearchModel;

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
                        'actions' => ['search'],
                        'allow' => true,
                        //'roles' => ['@'],
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
        $basket = [];
        $session = Yii::$app->session;
        $session->open();
        if ($session->has('basket')){
            $basket = $session->get('basket');
        }
            
        if(Yii::$app->request->isPjax && Yii::$app->request->post('basket')!==null)
        {
            $id = Yii::$app->request->post('basket')['id'];
            $count = Yii::$app->request->post('basket')['count'];
            $basket[$id] = $count;
        }
        
        $session->set('basket',$basket);
        $session->close();
        
        if (strlen(Yii::$app->request->get('article'))>0)
            $article = Yii::$app->request->get('article');
        else
            $article='';
        $this->view->params['article'] = $article;
        $search = new SearchModel($article);

        return $this->render('search',compact('search','basket'));
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