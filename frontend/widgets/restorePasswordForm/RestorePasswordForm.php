<?php
namespace frontend\widgets\restorePasswordForm;

use Yii;
use yii\base\Model;
use common\models\User;
use common\models\Role;

/**
 * Login form
 */
class RestorePasswordForm extends Model
{
    public $email;
    
    private $_user = false;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email'], 'required'],
            [['email'], 'trim'],
            ['email','registrationCheck'],
        ];
    }
    
    public function attributeLabels() {
        return [
            'email'=>'Ваш адрес электронной почты:',
        ];
    }
    
    public function registrationCheck($attribute)
    {
        $user = User::findByEmail($this->$attribute);
        //если пользователь не существует или его регистрация не подтверждена
        if ($user===null || $user->isRegistered()===false)
            //выводим ошибку
            $this->addError($attribute,'Почта не зарегистрирована!');
    }

    protected function getUser()
    {
        if ($this->_user === false)
            $this->_user = User::findByUsername($this->email);

        return $this->_user;
    }
    
    //устанавливает пользователю ключ для изменения пароля
    public function save($mailConfirmUrl)
    {
        if ($this->user === null)
            return false;
        
        return $this->user->setKeyForChangePassAndSendMailForConfirm($mailConfirmUrl);
    }
}