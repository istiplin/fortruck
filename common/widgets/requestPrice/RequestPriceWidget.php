<?php
namespace common\widgets\requestPrice;

use yii\base\Widget;

class RequestPriceWidget extends Widget {
    
    public $id;
    public $activeFormConfig = [];
    
    public function run() 
    {
        $data = [];
        $data['id'] = $this->id;
        $data['model'] = new RequestPriceForm();
        $data['activeFormConfig'] = $this->activeFormConfig;
        $data['activeFormConfig']['id'] = $this->id.'-form';
        return $this->render('index', $data);
    }
}