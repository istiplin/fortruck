<?php
namespace common\widgets\auth;

use Yii;
use yii\base\Action;
use yii\web\Response;
use common\models\LoginForm;

class AuthAction extends Action {
    
    public function run($args=null)
    {
        if (!strlen($actionName=Yii::$app->request->get('action')))
            $actionName = 'login';
        
        return $this->{$actionName}();
    }

    private function login()
    {
        if (!Yii::$app->request->isAjax)
            return Yii::$app->controller->renderContent('');
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $model = new LoginForm;

        $request = Yii::$app->getRequest();
        //if ($model->load($request->post()) AND $model->login())
        
        $model->attributes = $request->post();
        if ($model->login())
        {
            return [
                'success' => 1,
                'redirectUrl' => Yii::$app->request->post('redirectUrl')
            ];
        }
        else
        {
            return [
                'success' => 0,
                'errors' => $model->errors
            ];
        }
        
    }
    
    private function logout()
    {
        Yii::$app->user->logout();
        return Yii::$app->getResponse()->redirect(Yii::$app->request->get('redirectUrl'));
    }
}
?>
