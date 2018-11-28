<?php
namespace common\widgets\registration;

use yii\base\Model;

class RegistrationForm extends Model
{
    public $company_name;
    public $name;
    public $phone;
    public $email;
    
    private $_user;
    
    public function rules()
    {
        return [
            [['name', 'phone', 'email'], 'required'],
            [['name', 'phone', 'email', 'company_name'], 'trim'],
            ['email', 'match', 'pattern'=>'/^[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}$/i','message'=>'Адрес электронной почты введен в неправильном формате'],
            ['email','checkRegistration'],
            [['company_name'], 'safe']
        ];
    }
    
    public function attributeLabels() {
        return [
            'company_name'=>'Наименование организации:',
            'name'=>'Ваше имя:',
            'phone'=>'Ваш контактный телефон:',
            'email'=>'Ваш адрес электронной почты:',
        ];
    }
    
    public function checkRegistration($attribute)
    {
        $user = RegistrationUser::findByEmail($this->$attribute);
        //если пользователь существует и его регистрация подтверждена
        if ($user && $user->isRegistered())
            //выводим ошибку
            $this->addError($attribute,'Такой адрес уже зарегистрирован!');
    }
    
    public function getUser()
    {
        return $this->_user;
    }
    
    public function sendMailConfirmMessage($mailConfirmUrl)
    {
        $this->_user = $user = RegistrationUser::createByEmail($this->email);
        $user->attributes = $this->attributes;
        if ($user->sendMailConfirmMessage($mailConfirmUrl))
        {
            return [
                'success' => 1,
                'email' => $this->email,
            ];
        }
        return [
            'success' => 0,
        ];
    }
    
    public function confirmMail($id,$operation_key)
    {
        $this->_user = $user = RegistrationUser::findIdentity($id);
        return $user AND $user->confirmMail($operation_key);
    }
    
    public function register($id)
    {
        $this->_user = $user = RegistrationUser::findOne($id);
        return $user->register();
    }
}
