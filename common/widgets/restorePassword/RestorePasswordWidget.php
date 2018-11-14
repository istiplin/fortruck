<?php
namespace common\widgets\restorePassword;;

use yii\base\Widget;

class RestorePasswordWidget extends Widget {
    
    public $activeFormConfig = [];
    
    public function run() 
    {
        $data = [];
        $data['model'] = new RestorePasswordForm();
        $data['activeFormConfig'] = $this->activeFormConfig;
        $data['activeFormConfig']['action']['redirectUrl'] = \Yii::$app->request->url;
        return $this->render('index', $data);
    }
}