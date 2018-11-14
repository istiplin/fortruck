<?php
namespace common\widgets\registration;

use common\models\User;
use common\models\Role;
use common\models\Config;
use yii\db\Expression;

//класс пользователь, котором хранятся методы для регистрации
class RegistrationUser extends User
{
    //находит запись по $email или, если запись не существует, создает новую
    public static function createByEmail($email)
    {
        $user = static::findByEmail($email);
        if($user!==null)
            return $user;
        return new static;
    }
    
    //устанавливает пользователю ключ для регистрации
    public function setOperationKey()
    {
        if ($this->isRegistered())
            return false;
        $this->role_id = Role::getIdByAlias('registration_begin');
        $this->operation_key = \Yii::$app->security->generateRandomString();
        return $this->save();
    }
    
    //отправляет пользователю сообщение для подтверждения почты, 
    //где будет ссылка для подтверждения почты $mailConfirmUrl
    public function sendMailConfirmMessage($mailConfirmUrl)
    {
        $mailConfirmUrl['id'] = $this->id;
        $mailConfirmUrl['operation_key'] = $this->operation_key;
        
        \Yii::$app->mailer->compose('confirmMailForRegistration',[
                                            'userName'=>$this->name,
                                            'mailConfirmUrl'=>$mailConfirmUrl])
            ->setTo($this->email)
            ->setSubject('ForTruck. Заявка на регистрацию')
            ->send();
    }
    
    //подтверждает почту по ключу
    public function confirmMail($operation_key)
    {
        if($this->isRegistered())
            return false;
        
        if (strlen($operation_key)==0 OR $this->operation_key!==$operation_key)
            return false;
        
        $this->role_id = Role::getIdByAlias('mail_confirmed');   
        $this->operation_key = null;

        
        if (!$this->save())
            return false;
        
        \Yii::$app->mailer
            ->compose('registrationRequest',['user'=>$this])
            ->setTo(Config::value('site_email'))
            ->setSubject('Заявка на регистрацию')
            ->send();
        
        return true;
    }
    
    //регистрирует пользователя
    public function register($password=null)
    {
        if ($this->role->alias==='mail_confirmed')
        {
            if ($password===null)
                $password = $this->generatePassword();
            
            $this->setPassword($password);
            $this->role_id = Role::getIdByAlias('customer');
            $this->registration_data = new Expression('NOW()');
            
            if (!$this->save())
                return false;
            
            \Yii::$app->mailer->compose('newPasswordForRegistration',['password'=>$password, 'user'=>$this])
                    ->setTo($this->email)
                    ->setSubject('ForTruck. Заявка на регистрацию')
                    ->send();
            
            return true;
        }
        return false;
    }
}
?>
