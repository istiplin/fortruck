<?php
namespace common\components;

use common\models\Config;

class Mailer extends \yii\swiftmailer\Mailer
{
    public function __construct()
    {
        $config = Config::getValuesAll();
        
        $this->transport = [
            'class' => 'Swift_SmtpTransport',
            'host' => $config['host'],
            'port' => $config['port'],
            'encryption' => $config['encryption'],
            'username' => $config['site_email'],
            'password' => $config['site_email_password']
        ];
        
        $this->messageConfig['from']=[Config::value('site_email')=>'ForTruck'];
    }
}

?>
