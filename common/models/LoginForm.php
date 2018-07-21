<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\helpers\Html;
use yii\helpers\Url;
/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if ($user AND $user->role->alias=='registration_begin')
            {
                $this->addError($attribute, 'Почта не подтверждена. Пожалуйста подтвердите регистрацию или зарегистрируйтесь заново.');
                return;
            }
            if ($user AND $user->role->alias=='mail_confirmed')
            {
                $this->addError($attribute, 'Заявка пока не рассмотрена');
                return;
            }
            if (!$user OR strlen($this->password)>User::$passwordCharsMaxCount OR !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Неверный логин или пароль.');
                return;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Имя пользователя',
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить меня',
        ];
    }
    
    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            
            if ($this->rememberMe)
            {
                $user->generateAuthKey();
                $user->save();
            }
            
            return Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);
            
            //return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
