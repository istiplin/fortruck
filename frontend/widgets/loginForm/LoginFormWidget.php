<?php
namespace frontend\widgets\loginForm;

use yii\base\Widget;

class LoginFormWidget extends Widget {
    
    public $activeFormConfig = [];
    
    public function run() 
    {
        $data = [];
        $data['model'] = new LoginForm();
        $data['activeFormConfig'] = $this->activeFormConfig;
        return $this->render('loginFormWidget', $data);
    }
}