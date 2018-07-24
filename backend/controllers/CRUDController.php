<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class CRUDController extends Controller
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
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)==false)
            return false;
        
        if ($action->id==='index')
        {
            $sessionName = $this->id.'QueryParams';

            //если есть параметры для запроса
            if (count(Yii::$app->request->queryParams)!=0)
            {
                //запоминаем их
                Yii::$app->session->set($sessionName,Yii::$app->request->queryParams);
            }
            //иначе
            elseif (Yii::$app->session->has($sessionName))
            {
                $queryParams = Yii::$app->session->get($sessionName);
                Yii::$app->session->remove($sessionName);
                
                //если мы запомнили первую страницу
                if($queryParams['page']==1)
                    //делаем редирект без этих параметров
                    return $this->redirect(['index']);
                //иначе
                else
                    //делаем редирект с ними
                    return $this->redirect(array_merge(['index'],$queryParams));
            }
        }
        
        return true;
    }
}
?>
