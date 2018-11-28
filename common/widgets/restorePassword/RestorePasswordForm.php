<?php
namespace common\widgets\restorePassword;

use Yii;
use yii\base\Model;

class RestorePasswordForm extends Model
{
    public $email;

    private $_user;
    
    public function rules()
    {
        return [
            [['email'], 'required'],
            [['email'], 'trim'],
            ['email','checkRegistration'],
        ];
    }
    
    public function attributeLabels() {
        return [
            'email'=>'Ваш адрес электронной почты:',
        ];
    }
    
    public function checkRegistration($attribute)
    {
        $user = RestorePasswordUser::findByEmail($this->$attribute);
        //если пользователь не существует или его регистрация не подтверждена
        if ($user===null || $user->isRegistered()===false)
            //выводим ошибку
            $this->addError($attribute,'Почта не зарегистрирована!');
    }
    
    public function getUser()
    {
        return $this->_user;
    }
    
    public function sendMailConfirmMessage($mailConfirmUrl)
    {
        $this->_user = $user = RestorePasswordUser::findByEmail($this->email);
        if ($user AND $user->sendMailConfirmMessage($mailConfirmUrl))
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
        $this->_user = $user = RestorePasswordUser::findIdentity($id);
        return $user AND $user->confirmMail($operation_key);
    }
}