<?php 
namespace common\tests\unit\models;

use common\widgets\registration\RegistrationForm;
use common\widgets\restorePassword\RestorePasswordForm;

//класс для тестирования учетной записи пользователя (регистрация, смена пароля)
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

    // tests
    public function testSomeFeature()
    {
        //делаем валидацию данных с неверным форматом почты
        $reg = new RegistrationForm;
        $reg->attributes = [
            'email'=>'usertest.ru',
            'name'=>'new_user',
            'phone'=>'8-916-123-45-67',
        ];
        expect('email has incorrect format',$reg->validate())->false();
        
        //еще раз делаем валидацию данных с неверным форматом почты
        $reg = new RegistrationForm;
        $reg->attributes = [
            'email'=>'user@testru',
            'name'=>'user',
            'phone'=>'8-916-123-45-67',
        ];
        expect('email has incorrect format',$reg->validate())->false();
        
        //делаем валидацию данных уже с верным форматом почты
        $reg = new RegistrationForm;
        $email = 'user@test.ru';
        $reg->attributes = [
            'email'=>$email,
            'name'=>'user',
            'phone'=>'8-916-123-45-67',
        ];
        expect('email has correct format',$reg->validate())->true();
        
        //проходим начальную регистрацию, сохраняя введенные данные
        expect('form is saved',$reg->sendMailConfirmMessage(['anyArray'])['success'])->equals(1);
        
        $operation_key = $reg->user->operation_key;
        
        //еще раз пытаемся сохранить данные с той же почтой, но немного с другими данными
        //данные должны перезаписаться, т.к. сообщение о подтверждении регистрации может быть утеряно
        $reg = new RegistrationForm;
        $phone = '8-916-321-45-67';
        $reg->attributes = [
            'email'=>$email,
            'name'=>'user2',
            'phone'=>$phone,
        ];
        expect('form is saved',$reg->sendMailConfirmMessage(['anyArray'])['success'])->equals(1);
        
        //ожидаем, что телефон перезаписался
        expect('phone is equal',$reg->user->phone)->equals($phone);
        
        //ожидаем, что при перезаписи данных ключ изменился
        expect('operation key is not equal',$reg->user->operation_key)->notEquals($operation_key);
        
        //пытаемся подтвердить почту изпользуя старый ключ
        expect('mail not confirmed',$reg->confirmMail($reg->user->id,$operation_key))->false();
        
        //убеждаемся, что роль не изменена
        expect('role is registration_begin',$reg->user->role_id)->equals(0);
        
        //пытаемся восстановить пароль пользователю, который пока не прошел регистрацию
        $restPass = new RestorePasswordForm;
        $restPass->email = $email;
        $restPass->checkRegistration('email');
        expect('user not registered',$restPass->errors)->hasKey('email');
        expect('user not saved',$restPass->sendMailConfirmMessage(['anyArray'])['success'])->equals(0);
        
        //пытаемся подтвердить почту используя теперь правильный ключ
        expect('mail confirmed',$reg->confirmMail($reg->user->id,$reg->user->operation_key))->true();
        
        //убеждаемся, что роль изменена
        expect('role is mail_confirmed',$reg->user->role_id)->equals(1);
        
        //пытаемся восстановить пароль пользователю, пока тот не прошел регистрацию
        $restPass = new RestorePasswordForm;
        $restPass->email = $email;
        $restPass->checkRegistration('email');
        expect('user not registered',$restPass->errors)->hasKey('email');
        expect('user not saved',$restPass->sendMailConfirmMessage(['anyArray'])['success'])->equals(0);
        
        //еще раз пытаемся сохранить данные с той же почтой, но немного с другими данными
        //данные должны перезаписаться, т.к. сообщение о подачи заявки на регистрацию может быть утеряно
        //$email = 'user@test.ru';
        $reg = new RegistrationForm;
        $phone = '8-916-321-45-67';
        $reg->attributes = [
            'email'=>$email,
            'name'=>'user2',
            'phone'=>$phone,
        ];
        expect('form is saved',$reg->sendMailConfirmMessage(['anyArray'])['success'])->equals(1);
        
        //убеждаемся, что роль изменена
        expect('role is registration_begin',$reg->user->role_id)->equals(0);
        
        //подтверждаем почту
        expect('mail not confirmed',$reg->confirmMail($reg->user->id,$reg->user->operation_key))->true();
        
        //убеждаемся, что роль изменена
        expect('role is mail_confirmed',$reg->user->role_id)->equals(1);
        
        
        expect('user is registered',$reg->user->role->alias==='mail_confirmed')->true();
        //регистрируем пользователя
        expect('user is registered',$reg->register($reg->user->id))->true();
        
        //убеждаемся, что роль изменена
        expect('role is mail_confirmed',$reg->user->role_id)->equals(2);
        
        
        //еще раз пытаемся сохранить данные с той же почтой
        //тут данные уже не должны перезаписаться, т.к. почта зарегистрирована
        $reg = new RegistrationForm;
        $phone = '8-916-321-45-67';
        $reg->attributes = [
            'email'=>$email,
            'name'=>'user2',
            'phone'=>$phone,
        ];
        
        //делаем проверку на регистрацию, ожидая, что будет ошибка
        $reg->checkRegistration('email');
        expect('has error',$reg->errors)->hasKey('email');
        
        //пытаемся сохранить данные
        expect('form is not saved',$reg->sendMailConfirmMessage(['anyArray'])['success'])->equals(0);
        
        //убеждаемся, что роль не изменена
        expect('role is not change',$reg->user->role_id)->equals(2);
        
        
        //пытаемся восстановить пароль
        
        //пытаемся восстановить пароль пользователю, который не существует
        $restPass = new RestorePasswordForm;
        $restPass->email = 'no_user@test.ru';
        $restPass->checkRegistration('email');
        expect('user not registered',$restPass->errors)->hasKey('email');
        expect('user is null',$restPass->user)->null();
        expect('user not saved',$restPass->sendMailConfirmMessage(['anyArray'])['success'])->equals(0);
        
        //пытаемся восстановить пароль пользователю, который существует
        $restPass = new RestorePasswordForm;
        $restPass->email = $email;
        $restPass->checkRegistration('email');
        expect('user is registered',$restPass->errors)->hasntKey('email');
        expect('user saved',$restPass->sendMailConfirmMessage(['anyArray'])['success'])->equals(1);
        $operation_key = $restPass->user->operation_key;
        
        //не подтверждая восстановление пароля пытаемся еще раз получить подтверждение
        $restPass = new RestorePasswordForm;
        $restPass->email = $email;
        expect('user saved',$restPass->sendMailConfirmMessage(['anyArray'])['success'])->equals(1);
        $password = $restPass->user->password;
        
        //сверяем ключи
        expect('operation key changed',$restPass->user->operation_key)->notEquals($operation_key);
        
        //пытаемся восстановить пароль по старому ключу
        expect('restore password not confirmed',$restPass->confirmMail($restPass->user->id,$operation_key))->false();
        
        //проверяем, что пароли не изменились
        expect('password not changed',$restPass->user->password)->equals($password);
        
        //пытаемся восстановить пароль по новому ключу
        expect('restore password confirmed',$restPass->confirmMail($restPass->user->id,$restPass->user->operation_key))->true();
        
        //проверяем, что пароли изменились
        expect('password changed',$restPass->user->password)->notEquals($password);
        
        //проверяем, что ключ удален
        expect('operation key is empty',$restPass->user->operation_key)->isEmpty();
    }
}