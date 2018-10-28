<?php
namespace frontend\widgets\registrationForm;

use yii\base\Widget;

class RegistrationFormWidget extends Widget {
    
    public $activeFormConfig = [];
    
    public function run() 
    {
        $data = [];
        $data['model'] = new RegistrationForm();
        $data['activeFormConfig'] = $this->activeFormConfig;
        return $this->render('registrationFormWidget', $data);
    }
}