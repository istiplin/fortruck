<?php
namespace common\widgets\registration;

use Yii;
use yii\base\Action;
use yii\web\Response;
use yii\widgets\ActiveForm;

class RegistrationAction extends Action {

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
        
        $model = new RegistrationForm;
        
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
        
        $model = new RegistrationForm;
        $success = $model->confirmMail($id,$operation_key);
        
        Yii::$app->session->setFlash('is_success_registration',$success);
        Yii::$app->controller->redirect(Yii::$app->request->get('redirectUrl'));
    }
}
?>
