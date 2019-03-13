<?php 
namespace common\tests\unit\models;

use common\widgets\registration\RegistrationForm;
use common\widgets\restorePassword\RestorePasswordForm;
use common\models\LoginForm;

//класс для тестирования учетной записи пользователя (регистрация, смена пароля, авторизация)
class AccountTest extends \Codeception\Test\Unit
{
    /**
     * @var \frontend\tests\UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }
    
    private function initReg($attr)
    {
        $reg = new RegistrationForm;
        $reg->attributes = $attr;
        return $reg;
    }
    
    private function initRestPass($email)
    {
        $restPass = new RestorePasswordForm;
        $restPass->email = $email;
        return $restPass;
    }
    
    private function login($attr)
    {
        $login = new LoginForm;
        $login->attributes = $attr;
        return $login->login();
    }
    
    //изменяет пароль незарегистрированного пользователя
    private function restorePassNoRegUser($email)
    {
        $restPass = $this->initRestPass($email);
        $restPass->checkRegistration('email');
        expect('user not registered',$restPass->errors)->hasKey('email');
        expect('user not saved',$restPass->sendMailConfirmMessage(['anyArray'])['success'])->equals(0);
    }
    //регистрирует незарегистрированного пользователя
    private function registerNoRegisterUser($user,$newAttr)
    {
        $newAttr['email'] = $user->email;
        
        //опять пытаемся зарегистрироваться под этим же пользователем
        $reg = $this->initReg($newAttr);
        //делаем валидацию
        expect('email has correct format',$reg->validate())->true();
        //сохраняем введенные данные
        expect('form is saved',$reg->sendMailConfirmMessage(['anyArray'])['success'])->equals(1);
        
        $newUser = $reg->user;
        
        expect('user name was changed',$newUser->name)->equals($newAttr['name']);
        expect('user phone was changed',$newUser->phone)->equals($newAttr['phone']);
        
        expect('user operation key is not empty',$newUser->operation_key)->notEmpty();
        expect('user operation key was changed',$newUser->operation_key)->notEquals($user->operation_key);
    }
    
    //авторизовывается под зарегистрированным пользователем
    private function loginRegisterUser($attr)
    {
        //авторизовываемся под неверным паролем
        $errorAttr = $attr;
        $errorAttr['password'].= 't';
        expect('login not success',$this->login($errorAttr))->false();
        
        //авторизовываемся под неверным логином
        $errorAttr = $attr;
        $errorAttr['username'].= 't';
        expect('login not success',$this->login($errorAttr))->false();
        
        //авторизовываемся под верным логином и паролем
        expect('login success',$this->login($attr))->true();
    }
    //воззтанавливает пароль зарегистрированного пользователя
    private function restorePassRegUser($email)
    {
        $restPass = $this->initRestPass($email);
        $restPass->checkRegistration('email');
        
        expect('user not registered',$restPass->errors)->hasntKey('email');
        expect('user not saved',$restPass->sendMailConfirmMessage(['anyArray'])['success'])->equals(1);
        
        $user = $restPass->user;
        $oldPassword = $user->password;
        
        expect('operation key is not empty',$user->operation_key)->notEmpty();
        
        $restPass = new RestorePasswordForm();
        expect('restore password not confirmed',$restPass->confirmMail($user->id, $user->operation_key.'d'))->false();
        expect('restore password not confirmed',$restPass->confirmMail($user->id+1, $user->operation_key))->false();
        expect('restore password not confirmed',$restPass->confirmMail('fgfgfg', $user->operation_key))->false();
        
        expect('restore password confirmed',$restPass->confirmMail($user->id, $user->operation_key))->true();
        
        
        $user = $restPass->user;
        
        //проверяем, что пароли изменились
        expect('password changed',$user->password)->notEquals($oldPassword);
        
        return $user;
    }
    
    //регистрируется под пользователем, который уже зарегистрирован
    private function registerRegisterUser($user,$newAttr)
    {
        $newAttr['email'] = $user->email;
        
        //опять пытаемся зарегистрироваться под этим же пользователем
        $reg = $this->initReg($newAttr);
        //делаем валидацию
        expect('email has correct format',$reg->validate())->false();
    }
    
    //тестирует неправильно заполненную форму регистрации 
    public function testErrorRegForm()
    {
        $reg = $this->initReg([
            'email'=>'error1test.ru',
            'name'=>'error1',
            'phone'=>'8-916-123-45-67',
        ]);
        expect('email has incorrect format',$reg->validate())->false();
        
        $reg = $this->initReg([
            'email'=>'error2@testru',
            'name'=>'error2',
            'phone'=>'8-916-123-45-67',
        ]);
        expect('email has incorrect format',$reg->validate())->false();

    }
    
    //тестирует правильно заполненную форму регистрации
    public function testNoErrorRegForm()
    {
        $attr = [
            'email'=>'noerror@test.ru',
            'name'=>'noerror',
            'phone'=>'8-916-123-45-67',
        ];
        
        $reg = $this->initReg($attr);
        //делаем валидацию
        expect('email has correct format',$reg->validate())->true();
        //сохраняем введенные данные
        expect('form is saved',$reg->sendMailConfirmMessage(['anyArray'])['success'])->equals(1);
        
        $user = $reg->user;
        
        expect('operation key is not empty',$user->operation_key)->notEmpty();
        
        //проверяем наличие пароля
        expect('role is mail_confirmed',$reg->user->password)->isEmpty();
        //--------------------------------------------------------------------------------//
        //$this->loginNoRegisterUser

        //изменяем пароль незарегистрированного пользователя
        $this->restorePassNoRegUser($user['email']);
        
        //регистрируем незарегистрированного пользователя
        $this->registerNoRegisterUser($user,[
            'name'=>'noerror2',
            'phone'=>'8-916-123-45-68',
        ]);
    }
    
    //тестирует подтверждение почты
    public function testConfirmMail()
    {
        $attr = [
            'email'=>'confirm-mail@test.ru',
            'name'=>'confirm-mail',
            'phone'=>'8-916-123-45-67',
        ];
        //перед подтверждением почты отправим форму регистрации без ошибок
        $reg = $this->initReg($attr);
        $reg->sendMailConfirmMessage(['anyArray']);
        $user = $reg->user;
        
        //далее подтверждаем почту
        $reg = new RegistrationForm;
        
        //используем неправильный ключ
        expect('mail not confirmed',$reg->confirmMail($user->id,$user->operation_key.'f'))->false();
        
        //используем id другого пользователя
        expect('mail not confirmed',$reg->confirmMail($user->id+1,$user->operation_key))->false();
        expect('mail not confirmed',$reg->confirmMail('sdsd',$user->operation_key))->false();
        
        //используем правильный ключ
        expect('mail confirmed',$reg->confirmMail($user->id,$user->operation_key))->true();
        
        $user = $reg->user;
        
        //проверяем, что ключ удален
        expect('operation key is empty',$user->operation_key)->isEmpty();
        
        //убеждаемся, что роль изменена
        expect('role is mail_confirmed',$user->role_id)->equals(1);
        
        //проверяем наличие пароля
        expect('role is mail_confirmed',$user->password)->isEmpty();
        //--------------------------------------------------------------------------------//
        //$this->loginNoRegisterUser
        
        //изменяем пароль незарегистрированного пользователя
        $this->restorePassNoRegUser($user['email']);
        
        //регистрируем незарегистрированного пользователя
        $this->registerNoRegisterUser($user,[
            'name'=>'confirm-mail2',
            'phone'=>'8-916-123-45-68',
        ]);
    }
    
    //тестирует регистрацию пользователя
    public function testRegisterUser()
    {
        $attr = [
            'email'=>'register@test.ru',
            'name'=>'register',
            'phone'=>'8-916-123-45-67',
        ];
        
        //перед подтверждением почты отправим форму регистрации без ошибок
        $reg = $this->initReg($attr);
        $reg->sendMailConfirmMessage(['anyArray']);
        $user = $reg->user;
        
        //далее подтверждаем почту
        $reg = new RegistrationForm;
        $reg->confirmMail($user->id,$user->operation_key);
        $user = $reg->user;   
                
        //регистрируем пользователя
        $reg = new RegistrationForm;
        expect('user is registered',$reg->register($user->id))->true();
        
        $user = $reg->user;
        
        //убеждаемся, что роль изменена
        expect('role is mail_confirmed',$user->role_id)->equals(2);
        
        //проверяем наличие пароля
        expect('role is mail_confirmed',$user->password)->notEmpty();
        //--------------------------------------------------------------------------------//
        
        //авторизовываемся под зарегистрированным пользователем
        $this->loginRegisterUser([
            'username'=>$user->email,
            'password'=>$user->password
        ]);
        
        //изменяем пароль зарегистрированного пользователя
        $user = $this->restorePassRegUser($user['email']);
        
        //авторизовываемся под зарегистрированным пользователем
        $this->loginRegisterUser([
            'username'=>$user->email,
            'password'=>$user->password
        ]);

        //регистрируем пользователя, который уже зарегистрирован
        $this->registerRegisterUser($user,[
            'name'=>'register2',
            'phone'=>'8-916-123-45-68',
        ]);
    }
}