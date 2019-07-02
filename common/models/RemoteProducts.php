<?php

namespace common\models;

class RemoteProducts extends \yii\base\Behavior
{
    private $_apikey;
    public function getApikey()
    {
        if ($this->_apikey!==null)
            return $this->_apikey;
        return $this->_apikey = Config::value('api_key');
    }
    
    public function loadXML($url)
    {
        $xml = new \DOMDocument('1.0','utf-8');
        //проверка на чтение
        try
        {
            $arrContextOptions=[
                "ssl"=>[
                    "verify_peer"=>false,
                    "verify_peer_name"=>false,
                ],
            ];  
            $xmlText = file_get_contents($url, false, stream_context_create($arrContextOptions));
            $xml->loadXML($xmlText);
        }
        catch(\Exception $e)
        {
            echo $e->getMessage();
            
            if (\Yii::$app instanceof \yii\console\Application)
                return false;
            
            \Yii::$app->end();
        }
        
        $resultXml=$xml->getElementsByTagName('result')->item(0);
        
        //проверка результата загруки
        $typeResult = $resultXml->getAttribute('type');
        if ($typeResult!='ok')
        {
            echo "typeResult $typeResult";
           \Yii::$app->end();
        }
        
        //проверка ключа
        $apikey = $resultXml->getElementsByTagName('apikey')->item(0)->nodeValue;
        if ($apikey!==$this->apikey)
        {
            if (\Yii::$app instanceof \yii\console\Application)
            {
                echo "incorrect apikey: '$apikey'=/='{$this->apikey}'";
                \Yii::$app->end();
            }

            if(\Yii::$app->user->identity->isAdmin())
                throw new \Exception("Неверный API KEY: {$this->apikey}'");
            throw new \Exception('Нет данных!');

        }
        
        return $resultXml;
    }
}