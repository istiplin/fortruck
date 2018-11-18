<?php
namespace common\widgets\registration;

use yii\base\Widget;

class RegistrationWidget extends Widget {
    
    public $id;
    public $activeFormConfig = [];
    
    public function run() 
    {
        $data = [];
        $data['id'] = $this->id;
        $data['model'] = new RegistrationForm();
        $data['activeFormConfig'] = $this->activeFormConfig;
        $data['activeFormConfig']['id'] = $this->id.'-form';
        $data['activeFormConfig']['action']['redirectUrl'] = \Yii::$app->request->url;
        return $this->render('index', $data);
    }
}