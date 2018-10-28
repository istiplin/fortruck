<?php
namespace frontend\widgets\restorePasswordForm;

use Yii;
use yii\base\Action;
use yii\web\Response;
use yii\widgets\ActiveForm;
use common\models\User;

class RestorePasswordFormAction extends Action {
    
    public $mailConfirmUrl;
    public $methodName;
    public $redirectUrl;
    
    public function run($args=null)
    {
        return $this->{$this->methodName}();
    }
    
    //отправляет сообщение о подтверждении почты, для восстановления пароля
    public function sendConfirmMessage()
    {
        if (!Yii::$app->request->isAjax)
            return false;
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $model = new RestorePasswordForm;
        $request = Yii::$app->getRequest();
        
        if ($request->isPost && $model->load($request->post()))
        {
            if ($model->save($this->mailConfirmUrl))
            {
                return [
                    'success' => 1,
                    'email' => $model->email,
                ];
            }
            else
            {
                return [
                    'success' => 0,
                    'messages' => ActiveForm::validate($model)
                ];
            }
        }
    }
    
    //проверяет данные перед отправкой формы
    public function validate()
    {
        if (!Yii::$app->request->isAjax)
            return false;
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $model = new RestorePasswordForm;
        $request = Yii::$app->getRequest();
        
        if ($request->isPost && $model->load($request->post())) {
            return ActiveForm::validate($model);
        }
    }
    
    //восстанавливает пароль после подтверждения почты
    public function сhangePassword()
    {
        $id = Yii::$app->getRequest()->get('id');
        $operation_key = Yii::$app->getRequest()->get('operation_key');
        $user = User::findIdentity($id);
        
        if ($user)
        {
            $success = $user->confirmChangePassword($operation_key);
            Yii::$app->session->setFlash('is_success_restore_password',$success);
            Yii::$app->controller->redirect($this->redirectUrl);
        }
        else
        {
            Yii::$app->session->setFlash('is_success_restore_password',false);
            Yii::$app->controller->redirect($this->redirectUrl);
        }
    }

}
?>
