<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\RegistrationForm;
use yii\web\Response;
use yii\widgets\ActiveForm;
use common\models\User;

class RegistrationController extends Controller
{
    //public $layout = 'header';
    
    public function actionIndex()
    {
        $registration = new RegistrationForm;
        return $this->render('index',compact('registration'));
    }
    
    //проверяет данные при регистрации
    public function actionValidate()
    {
        $registration = new RegistrationForm;
        $request = Yii::$app->getRequest();
        if ($request->isPost && $registration->load($request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($registration);
        }
    }
    
    //сохраняет введенные данные при регистрации
    public function actionSave()
    {
        $registration = new RegistrationForm;
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($registration->load(Yii::$app->request->post()))
        {
            return [
                'success' => $registration->save(),
                'email' => $registration->email,
            ];
        }
        return ['success' => 0];
    }
    
    //подтверждает регистрацию
    public function actionConfirmRegistration($id,$operation_key)
    {
        $user = User::findIdentity($id);
        
        if ($user)
            return $this->render('confirm_mail',['success' => $user->confirmRegistration($operation_key)]);
        
        return $this->render('confirm_mail',['success' => 0]);
    }
}
?>
