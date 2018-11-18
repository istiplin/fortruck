<?php
namespace common\widgets\requestPrice;

use Yii;
use yii\base\Action;
use yii\web\Response;
use yii\widgets\ActiveForm;

class RequestPriceAction extends Action {

    public function run($args=null)
    {
        if (!strlen($actionName=Yii::$app->request->get('action')))
            $actionName = 'submit';
        
        return $this->{$actionName}();
    }
    
    private function submit()
    {
        if (!Yii::$app->request->isAjax)
            return false;
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $model = new RequestPriceForm;
        
        $request = Yii::$app->getRequest();
        if (!$request->isPost OR !$model->load($request->post()))
            return ['success' => 0];
        
        if ($model->validate())
        {
            return $model->sendMail();
        }
        else
        {
            return [
                'success' => 0,
                'messages' => ActiveForm::validate($model)
            ];
        }
    }
}
?>
