<?php
namespace frontend\widgets\restorePasswordForm;

use yii\base\Widget;

class RestorePasswordFormWidget extends Widget {
    
    public $activeFormConfig = [];
    
    public function run() 
    {
        $data = [];
        $data['model'] = new RestorePasswordForm();
        $data['activeFormConfig'] = $this->activeFormConfig;
        return $this->render('restorePasswordFormWidget', $data);
    }
}