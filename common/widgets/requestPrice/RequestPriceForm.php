<?php
namespace common\widgets\RequestPrice;

use yii\base\Model;
use common\models\Config;
use common\models\Product;

class RequestPriceForm extends Model
{
    public $name;
    public $phone;
    public $email;
    public $number;
    
    //private $_user;
    
    public function rules()
    {
        return [
            [['name', 'phone', 'number'], 'required'],
            [['name', 'phone', 'email', 'number'], 'trim'],
            ['email', 'match', 'pattern'=>'/^[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}$/i','message'=>'Адрес электронной почты введен в неправильном формате'],
            ['number', 'checkNumber']
        ];
    }
    
    public function attributeLabels() {
        return [
            'number'=>'Запрашиваемый артикул:',
            'name'=>'Ваше имя:',
            'phone'=>'Ваш контактный телефон:',
            'email'=>'Ваш адрес электронной почты:',
        ];
    }
    
    public function checkNumber($attr)
    {
        $product = Product::findOne(['number'=>$this->$attr]);
        if ($product===null)
            $this->addError($attr,'Товар с таким артиклом не существует');
    }
    
    //public function getUser()
    //{
    //    return $this->_user;
    //}
    
    public function sendMail()
    {
        \Yii::$app->mailer
            ->compose('requestPrice',['request'=>$this])
            ->setTo(Config::value('site_email'))
            ->setSubject('Запрос цены на товар')
            ->send();
        
        return ['success'=>1];
    }
}
