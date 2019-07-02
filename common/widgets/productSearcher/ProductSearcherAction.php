<?php
namespace common\widgets\productSearcher;

use yii\base\Action;
use frontend\models\Products;
use common\models\Config;

class productSearcherAction extends Action{
    public function run($args=null)
    {
        $number = $_GET['number'];
        $brandName = $_GET['brandName'];
        
        if (strlen($number)===0)
            return '';
        
        $search = Products::initial($number,$brandName,Config::value('is_remote'));
        if ($search instanceof \frontend\models\LookupProducts)
            return $this->controller->renderFile(__DIR__.'/views/lookup.php',compact('search'));
        if ($search instanceof \frontend\models\OffersProducts)
            return $this->controller->renderFile(__DIR__.'/views/offers.php',compact('search'));
    }
}