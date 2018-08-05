<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\RestorePasswordForm;
use yii\web\Response;
use yii\widgets\ActiveForm;
use common\models\User;

class RestorePasswordController extends Controller
{
    
    public function actionIndex()
    {
        $model = new RestorePasswordForm;
        return $this->render('index',compact('model'));
    }
    
    public function actionValidate()
    {
        $model = new RestorePasswordForm;
        $request = Yii::$app->getRequest();
        if ($request->isPost && $model->load($request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }
    
    //отправляет сообщение на почту для подтверждения
    public function actionSendConfirmMessageOnMail()
    {
        $post = Yii::$app->getRequest()->post('RestorePasswordForm');
        $user = User::findByEmail($post['email']);
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        if ($user)
        {
            return [
                'success' => $user->setOperationKeyForChangePassword(),
                'email' => $user->email,
            ];
        }
        
        return ['success' => 0];
    }
    
    public function actionChangePassword($id,$operation_key)
    {
        $user = User::findIdentity($id);
        if ($user)
            return $this->render('confirm_change_password',['success' => $user->confirmChangePassword($operation_key)]);
        
        return $this->render('confirm_change_password',['success' => 0]);
    }
}