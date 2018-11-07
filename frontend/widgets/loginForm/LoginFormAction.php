<?php
namespace frontend\widgets\loginForm;

use Yii;
use yii\base\Action;
use yii\web\Response;
use yii\widgets\ActiveForm;
use common\models\User;

class LoginFormAction extends Action {
    
    public $methodName;
    
    //возвращает параметры для инициализации объекта текущего класса
    public static function getInitParams($methodName,$params=array())
    {
        $initParams = [
            'class'=>self::className(),
            'methodName'=>$methodName
        ];
        
        foreach($params as $key=>$param)
            $initParams[$key]=$param;
        
        return $initParams;
    }
    
    public function run($args=null)
    {
        return $this->{$this->methodName}();
    }

    public function login()
    {
        $login = new LoginForm;
        //если успешно авторизовались
        if ($login->load(Yii::$app->request->post()) && $login->login())
            Yii::$app->getResponse()->redirect(Yii::$app->request->post('redirectUrl'));
    }
    
    public function validate()
    {
        if (!Yii::$app->request->isAjax)
            return false;
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $model = new LoginForm;
        $request = Yii::$app->getRequest();
            
        if ($request->isPost && $model->load($request->post())) {
            return ActiveForm::validate($model);
        }
    }
    
    public function logout()
    {
        Yii::$app->user->logout();
        return Yii::$app->getResponse()->redirect(Yii::$app->request->get('redirectUrl'));
    }

}
?>
