<?php 
namespace frontend\tests\unit\models;

use frontend\widgets\registrationForm\RegistrationForm;
use frontend\widgets\restorePasswordForm\RestorePasswordForm;
use common\models\User;

class RegistrationFormTest extends \Codeception\Test\Unit
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

    // tests
    public function testSomeFeature()
    {
        //делаем валидацию данных с неверным форматом почты
        $regForm = new RegistrationForm;
        $regForm->attributes = [
            'email'=>'usertest.ru',
            'name'=>'new_user',
            'phone'=>'8-916-123-45-67',
        ];
        expect('email has incorrect format',$regForm->validate())->false();
        
        //еще раз делаем валидацию данных с неверным форматом почты
        $regForm = new RegistrationForm;
        $regForm->attributes = [
            'email'=>'user@testru',
            'name'=>'user',
            'phone'=>'8-916-123-45-67',
        ];
        expect('email has incorrect format',$regForm->validate())->false();
        
        //делаем валидацию данных уже с верным форматом почты
        $regForm = new RegistrationForm;
        $email = 'user@test.ru';
        $regForm->attributes = [
            'email'=>$email,
            'name'=>'user',
            'phone'=>'8-916-123-45-67',
        ];
        expect('email has correct format',$regForm->validate())->true();
        
        //проходим начальную регистрацию, сохраняя введенные данные
        expect('form is saved',$regForm->save('http'))->true();
        
        $operation_key = $regForm->user->operation_key;
        
        //еще раз пытаемся сохранить данные с той же почтой, но немного с другими данными
        //данные должны перезаписаться, т.к. сообщение о подтверждении регистрации может быть утеряно
        $regForm = new RegistrationForm;
        $phone = '8-916-321-45-67';
        $regForm->attributes = [
            'email'=>$email,
            'name'=>'user2',
            'phone'=>$phone,
        ];
        expect('form is saved',$regForm->save('http'))->true();
        
        //ожидаем, что телефон перезаписался
        expect('phone is equal',$regForm->user->phone)->equals($phone);
        
        //ожидаем, что при перезаписи данных ключ изменился
        expect('operation key is not equal',$regForm->user->operation_key)->notEquals($operation_key);
        
        //пытаемся подтвердить почту изпользуя старый ключ
        expect('mail not confirmed',$regForm->user->confirmRegistration($operation_key))->false();
        
        //убеждаемся, что роль не изменена
        expect('role is registration_begin',$regForm->user->role_id)->equals(0);
        
        //пытаемся восстановить пароль пользователю, который пока тот не прошел регистрацию
        $restForm = new RestorePasswordForm;
        $restForm->email = $email;
        $restForm->registrationCheck('email');
        expect('user not registered',$restForm->errors)->hasKey('email');
        expect('user not saved',$restForm->save('http'))->false();
        
        //пытаемся подтвердить почту изпользуя теперь правильный ключ
        expect('mail not confirmed',$regForm->user->confirmRegistration($regForm->user->operation_key))->true();
        
        //убеждаемся, что роль изменена
        expect('role is mail_confirmed',$regForm->user->role_id)->equals(1);
        
        //пытаемся восстановить пароль пользователю, пока тот не прошел регистрацию
        $restForm = new RestorePasswordForm;
        $restForm->email = $email;
        $restForm->registrationCheck('email');
        expect('user not registered',$restForm->errors)->hasKey('email');
        expect('user not saved',$restForm->save('http'))->false();
        
        //еще раз пытаемся сохранить данные с той же почтой, но немного с другими данными
        //данные должны перезаписаться, т.к. сообщение о подачи заявки на регистрацию может быть утеряно
        //$email = 'user@test.ru';
        $regForm = new RegistrationForm;
        $phone = '8-916-321-45-67';
        $regForm->attributes = [
            'email'=>$email,
            'name'=>'user2',
            'phone'=>$phone,
        ];
        expect('form is saved',$regForm->save('http'))->true();
        
        //убеждаемся, что роль изменена
        expect('role is registration_begin',$regForm->user->role_id)->equals(0);
        
        //подтверждаем почту
        expect('mail not confirmed',$regForm->user->confirmRegistration($regForm->user->operation_key))->true();
        
        //убеждаемся, что роль изменена
        expect('role is mail_confirmed',$regForm->user->role_id)->equals(1);
        
        //регистрируем пользователя
        expect('user is registered',$regForm->user->register())->true();
        
        //убеждаемся, что роль изменена
        expect('role is mail_confirmed',$regForm->user->role_id)->equals(2);
        
        
        //еще раз пытаемся сохранить данные с той же почтой
        //тут данные уже не должны перезаписаться, т.к. почта зарегистрирована
        $regForm = new RegistrationForm;
        $phone = '8-916-321-45-67';
        $regForm->attributes = [
            'email'=>$email,
            'name'=>'user2',
            'phone'=>$phone,
        ];
        
        //делаем проверку на регистрацию, ожидая, что будет ошибка
        $regForm->registrationCheck('email');
        expect('has error',$regForm->errors)->hasKey('email');
        
        //пытаемся сохранить данные
        expect('form is not saved',$regForm->save('http'))->false();
        
        //убеждаемся, что роль не изменена
        expect('role is not change',$regForm->user->role_id)->equals(2);
        
        
        //пытаемся восстановить пароль
        
        //пытаемся восстановить пароль пользователю, который не существует
        $restForm = new RestorePasswordForm;
        $restForm->email = 'no_user@test.ru';
        $restForm->registrationCheck('email');
        expect('user not registered',$restForm->errors)->hasKey('email');
        expect('user is null',$restForm->user)->null();
        expect('user not saved',$restForm->save('http'))->false();
        
        //пытаемся восстановить пароль пользователю, который существует
        $restForm = new RestorePasswordForm;
        $restForm->email = $email;
        $restForm->registrationCheck('email');
        expect('user is registered',$restForm->errors)->hasntKey('email');
        expect('user is not null',$restForm->user)->notNull();
        expect('user saved',$restForm->save('http'))->true();
        $operation_key = $restForm->user->operation_key;
        
        //не подтверждая восстановление пароля пытаемся еще раз получить подтверждение
        $restForm = new RestorePasswordForm;
        $restForm->email = $email;
        expect('user saved',$restForm->save('http'))->true();
        $password = $restForm->user->password;
        
        //сверяем ключи
        expect('operation key changed',$restForm->user->operation_key)->notEquals($operation_key);
        
        //пытаемся восстановить пароль по старому ключу
        expect('restore password not confirmed',$restForm->user->confirmChangePassword($operation_key))->false();
        
        //проверяем, что пароли не изменились
        expect('password not changed',$restForm->user->password)->equals($password);
        
        //пытаемся восстановить пароль по новому ключу
        expect('restore password confirmed',$restForm->user->confirmChangePassword($restForm->user->operation_key))->true();
        
        //проверяем, что пароли изменились
        expect('password changed',$restForm->user->password)->notEquals($password);
        
        //проверяем, что ключ удален
        expect('operation key is empty',$restForm->user->operation_key)->isEmpty();
    }
}