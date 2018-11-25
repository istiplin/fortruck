<?php
namespace common\widgets\auth;

use yii\base\Widget;

class AuthWidget extends Widget {
    
    public $id;
    public $activeFormConfig = [];

    public static function getLogoutUrl($logoutRoute)
    {
        return [$logoutRoute,'action'=>'logout','redirectUrl'=>\Yii::$app->request->url];
    }
    
    public function run() 
    {
        $data = [];
        $data['id'] = $this->id;
        $data['activeFormConfig'] = $this->activeFormConfig;
        $data['activeFormConfig']['enableClientValidation'] = false;
        $data['activeFormConfig']['id'] = $this->id.'-form';
        
        $data['showModalLoginForm'] = \Yii::$app->controller->route===\Yii::$app->user->loginUrl[0];
        $data['redirectUrlAfterLogin'] = ($data['showModalLoginForm'])?(\Yii::$app->user->returnUrl):(\Yii::$app->request->url);
        
        return $this->render('index', $data);
    }
}