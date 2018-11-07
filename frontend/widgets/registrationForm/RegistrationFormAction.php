<?php
namespace frontend\widgets\registrationForm;

use Yii;
use yii\base\Action;
use yii\web\Response;
use yii\widgets\ActiveForm;
use common\models\User;

class RegistrationFormAction extends Action {

    public $methodName;
    
    public $mailConfirmUrl; //URL по которой надо перейти, чтобы подтвердить почту
    public $redirectUrl;
    
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
    
    //сохраняет данные формы
    //сохраняет введенные данные при регистрации
    public function save()
    {
        if (!Yii::$app->request->isAjax)
            return false;
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $model = new RegistrationForm;
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
    
    //проверяет данные при регистрации
    public function validate()
    {
        if (!Yii::$app->request->isAjax)
            return false;
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $registration = new RegistrationForm;
        $request = Yii::$app->getRequest();
            
        if ($request->isPost && $registration->load($request->post())) {
            return ActiveForm::validate($registration);
        }
    }
    
    //подтверждает регистрацию
    public function confirmRegistration()
    {
        $id = Yii::$app->getRequest()->get('id');
        $operation_key = Yii::$app->getRequest()->get('operation_key');
        $user = User::findIdentity($id);
        
        if ($user)
        {
            $success = $user->confirmRegistration($operation_key);
            Yii::$app->session->setFlash('success_registration',$success);
            Yii::$app->controller->redirect($this->redirectUrl);
        }
        else
        {
            Yii::$app->session->setFlash('success_registration',false);
            Yii::$app->controller->redirect($this->redirectUrl);
        }
    }

}
?>
