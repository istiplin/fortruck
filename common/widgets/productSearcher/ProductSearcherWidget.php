<?php
namespace common\widgets\productSearcher;

use yii\base\Widget;
class ProductSearcherWidget extends Widget {
    public $id;
    public $route;
    public function run() 
    {
        $data = [];
        $data['id'] = $this->id;
        $data['route'] = $this->route;
        return $this->render('index',$data);
    }
}
