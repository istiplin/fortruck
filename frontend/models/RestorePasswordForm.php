<?php
namespace frontend\models;

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

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email'], 'required'],
            [['email'], 'trim'],
            ['email', 'match', 'pattern'=>'/^[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}$/i','message'=>'Адрес электронной почты введен в неправильном формате'],
            ['email','registrationCheck'],
        ];
    }
    
    public function registrationCheck($attribute)
    {
        $user = User::findByEmail($this->$attribute);
        //если пользователь существует и его регистрация подтверждена
        if ($user===null || $user->isRegistered()===false)
            //выводим ошибку
            $this->addError($attribute,'Почта не зарегистрирована!');
    }
    
    public function attributeLabels() {
        return [
            'email'=>'Ваш адрес электронной почты:',
        ];
    }
}