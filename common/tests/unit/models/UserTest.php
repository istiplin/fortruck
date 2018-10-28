<?php 
namespace common\tests\unit\models;

use common\models\User;
use yii\db\Expression;

class UserTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
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
        //добавляем зарегистрированного пользователя
        $user = new User;
        $user->attributes = [
            'email'=>'user@test.ru',
            'name'=>'user',
            'password'=>'1234',
            'phone'=>'8-916-123-45-67',
            'company_name'=>'comp1',
            'role_id'=>'2',
            'registration_data'=>new Expression('NOW()')
        ];
        expect('user saved',$user->save())->true();
        
        //добавляем такого же пользователя, ожидая увидеть ошибку
        $user = new User;
        $user->attributes = [
            'email'=>'user@test.ru',
            'name'=>'user',
            'phone'=>'8-916-123-45-67',
            'role_id'=>'0',
        ];
        expect('user already registered',$user->save())->false();
        
        //пытаемся пройти начальную регистрацию нового пользователя с почтой, имеющей неправильный формат
        $user = new User;
        $user->attributes = [
            'email'=>'new_usertest.ru',
            'name'=>'new_user',
            'phone'=>'8-916-123-45-67',
            'company_name'=>'comp1',
        ];
        expect('email has incorrect format',$user->saveAndSendMailForConfirmRegistr('http'))->false();
        
        //пытаемся сделать тоже самое, но с правильным форматом
        $user = new User;
        $phone = '8-916-666-45-67';
        $user->attributes = [
            'email'=>'new_user@test.ru',
            'name'=>'new_user',
            'phone'=>$phone,
            'company_name'=>'comp1',
        ];
        expect('begin registration is success',$user->saveAndSendMailForConfirmRegistr('http'))->true();
        
        //проверяем телефон пользователя
        $user2 = User::findIdentity($user->id);
        $operation_key = $user2->operation_key;
        expect('phone is equal',$user2->phone)->equals($phone);
        
        expect('user is not registered',$user2->register())->false();
        expect('user is not registered',$user2->isRegistered())->false();
        
        //пытаемся подтвердить почту изпользуя неправильный ключ
        expect('mail not confirmed',$user2->confirmRegistration($operation_key.'1'))->false();
        
        //убеждаемся, что роль не изменена
        expect('role is registration_begin',$user2->role_id)->equals(0);
        
        //пытаемся сменить пароль
        expect('begin change password is not success',$user2->setKeyForChangePassAndSendMailForConfirm('http'))->false();
        
        //пытаемся еще раз подтвердить почту изпользуя уже правильный ключ
        expect('mail confirmed',$user2->confirmRegistration($operation_key))->true();
        
        //убеждаемся, что роль изменена
        expect('role is mail_confirmed',$user2->role_id)->equals(1);
        
        //убеждаемся, что ключ удален
        expect('key is empty',$user2->operation_key)->isEmpty();
        
        //пытаемся сменить пароль
        expect('begin change password is not success',$user2->setKeyForChangePassAndSendMailForConfirm('http'))->false();
        
        //убеждаемся, что ключа нет
        expect('key is empty',$user2->operation_key)->isEmpty();
        
        //регистрируем пользователя
        expect('user is registered',$user2->register())->true();
        
        expect('user is registered',$user2->isRegistered())->true();
        
        //еще раз пытаемся зарегистрировать пользователя
        expect('user is already registered',$user2->register())->false();
        expect('user is registered',$user2->isRegistered())->true();

        expect('key is not empty',$user2->password)->notEmpty();
        
        //убеждаемся, что роль изменена
        expect('role is customer',$user2->role_id)->equals(2);
        
        //пытаемся сменить пароль
        expect('begin change password is success',$user2->setKeyForChangePassAndSendMailForConfirm('http'))->true();
        
        //убеждаемся, что ключ есть
        expect('key is not empty',$user2->operation_key)->notEmpty();
        
        $oldPassword = $user2->password;
        
        //меняем пароль, используя неправильный ключ
        expect('end change password is not success', $user2->confirmChangePassword($user2->operation_key.'dfdf'))->false();
        
        //проверяем изменился ли пароль
        expect('password is not change',$user2->password)->equals($oldPassword);
        
        //убеждаемся, что ключ есть
        expect('key is not empty',$user2->operation_key)->notEmpty();
        
        //меняем пароль, используя уже правильный ключ
        expect('end change password is success', $user2->confirmChangePassword($user2->operation_key))->true();
        
        //проверяем изменился ли пароль
        expect('password is not change',$user2->password)->notEquals($oldPassword);
        
        //убеждаемся, что ключа нет
        expect('key is empty',$user2->operation_key)->isEmpty();
    }
}