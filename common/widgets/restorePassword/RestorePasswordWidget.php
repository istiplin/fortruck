<?php
namespace common\widgets\restorePassword;;

use yii\base\Widget;

class RestorePasswordWidget extends Widget {
    
    public $id;
    public $activeFormConfig = [];
    
    public function run() 
    {
        $data = [];
        $data['id'] = $this->id;
        $data['model'] = new RestorePasswordForm();
        $data['activeFormConfig'] = $this->activeFormConfig;
        $data['activeFormConfig']['id'] = $this->id.'-form';
        $data['activeFormConfig']['action']['redirectUrl'] = \Yii::$app->request->url;
        return $this->render('index', $data);
    }
}