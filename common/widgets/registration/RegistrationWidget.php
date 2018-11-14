<?php
namespace common\widgets\registration;

use yii\base\Widget;

class RegistrationWidget extends Widget {
    
    public $activeFormConfig = [];
    
    public function run() 
    {
        $data = [];
        $data['model'] = new RegistrationForm();
        $data['activeFormConfig'] = $this->activeFormConfig;
        $data['activeFormConfig']['action']['redirectUrl'] = \Yii::$app->request->url;
        return $this->render('index', $data);
    }
}