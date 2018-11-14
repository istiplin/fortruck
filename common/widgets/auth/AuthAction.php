<?php
namespace common\widgets\auth;

use Yii;
use yii\base\Action;
use yii\web\Response;
use yii\widgets\ActiveForm;

class AuthAction extends Action {
    
    public function run($args=null)
    {
        if (!strlen($actionName=Yii::$app->request->get('action')))
            $actionName = 'login';
        
        return $this->{$actionName}();
    }

    private function login()
    {
        $model = new AuthForm;
        //если успешно авторизовались
        if ($model->load(Yii::$app->request->post()) && $model->login())
            return Yii::$app->getResponse()->redirect(Yii::$app->request->post('redirectUrl'));
        return Yii::$app->controller->renderContent('');
    }
    
    private function validate()
    {
        if (!Yii::$app->request->isAjax)
            return false;
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $model = new AuthForm;
        $request = Yii::$app->getRequest();
            
        if ($request->isPost && $model->load($request->post())) {
            return ActiveForm::validate($model);
        }
    }
    
    private function logout()
    {
        Yii::$app->user->logout();
        return Yii::$app->getResponse()->redirect(Yii::$app->request->get('redirectUrl'));
    }

}
?>
