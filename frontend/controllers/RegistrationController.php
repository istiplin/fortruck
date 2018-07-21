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
        if ($registration->load(Yii::$app->request->post()) and $registration->save())
        {
            $user = $registration->user;
            //отправляем сообщение на подтверждение почты для регистрации на сайте
            Yii::$app->mailer->compose('confirmMailForRegistration',compact('user'))
                            //->setFrom('for.truck@mail.ru')
                            //->setFrom(['istiplin@gmail.com'=>'ForTruck'])
                            ->setTo($user->email)
                            ->setSubject('Заявка на регистрацию')
                            ->send();
            return [
                'success' => 1,
                'email' => $registration->email,
            ];
        }
        return ['success' => 0];
    }
    

    public function actionConfirmMail($id,$operation_key)
    {
        $user = User::findIdentity($id);
        
        if ($user AND $user->confirmMail($operation_key))
        {
            Yii::$app->mailer->compose('registrationRequest',compact('user'))
                            ->setTo(Yii::$app->mailer->transport->getUserName())
                            ->setSubject('Заявка на регистрацию')
                            ->send();
            return $this->render('confirm_mail',['success' => 1]);
        }
        
        return $this->render('confirm_mail',['success' => 0]);
    }
}
?>
