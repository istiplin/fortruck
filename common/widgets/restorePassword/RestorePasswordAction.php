<?php
namespace common\widgets\restorePassword;

use Yii;
use yii\base\Action;
use yii\web\Response;
use yii\widgets\ActiveForm;

class RestorePasswordAction extends Action {
    
    public function run($args=null)
    {
        if (!strlen($actionName=Yii::$app->request->get('action')))
            $actionName = 'submit';
        
        return $this->{$actionName}();
    }
    
    private function submit()
    {
        if (!Yii::$app->request->isAjax)
            return false;
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $model = new RestorePasswordForm;
        
        $request = Yii::$app->getRequest();
        if (!$request->isPost OR !$model->load($request->post()))
            return ['success' => 0];
        
        if ($model->validate())
        {
            $mailConfirmUrl = [
                Yii::$app->controller->route,
                'action'=>'сonfirmMail',
                'redirectUrl'=>Yii::$app->request->get('redirectUrl')
            ];
            return $model->sendMailConfirmMessage($mailConfirmUrl);
        }
        else
        {
            return [
                'success' => 0,
                'messages' => ActiveForm::validate($model)
            ];
        }
    }
    
    private function сonfirmMail()
    {
        $id = Yii::$app->getRequest()->get('id');
        $operation_key = Yii::$app->getRequest()->get('operation_key');
        
        $model = new RestorePasswordForm;
        $success = $model->confirmMail($id,$operation_key);
        
        Yii::$app->session->setFlash('is_success_restore_password',$success);
        Yii::$app->controller->redirect(Yii::$app->request->get('redirectUrl'));
    }
}
?>
