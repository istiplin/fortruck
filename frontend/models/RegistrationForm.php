<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use common\models\Role;

/**
 * Login form
 */
class RegistrationForm extends Model
{
    public $company_name;
    public $name;
    public $phone;
    public $email;

    private $_user;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'phone', 'email'], 'required'],
            [['name', 'phone', 'email', 'company_name'], 'trim'],
            ['email', 'match', 'pattern'=>'/^[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}$/i','message'=>'Адрес электронной почты введен в неправильном формате'],
            ['email','registrationCheck'],
            //[['company_name', 'name', 'phone', 'email'], 'required'],
            //['company_name', 'string', 'length' => [3,50]],
            //['name', 'string', 'length' => [1,40]],
            //['phone', 'string', 'length' => [7,18]],
            //['email', 'string', 'length' => [4,60]],
            [['company_name'], 'safe']
        ];
    }
    
    public function registrationCheck($attribute)
    {
        $user = User::findByEmail($this->$attribute);
        //если пользователь существует и его регистрация подтверждена
        if ($user && $user->isRegistered())
        {
            //выводим ошибку
            $this->addError($attribute,'Такой адрес уже зарегистрирован!');
        }
    }
    
    public function attributeLabels() {
        return [
            'company_name'=>'Наименование организации:',
            'name'=>'Ваше имя:',
            'phone'=>'Ваш контактный телефон:',
            'email'=>'Ваш адрес электронной почты:',
        ];
    }
    
    public function getUser()
    {
        if ($this->_user!==null)
            return $this->_user;
        
        return $this->_user = User::findByEmail($this->email);
    }
    
    public function save()
    {
        $user = $this->user;
        //если пользователь существует и его почта подтверждена
        if ($user AND $user->isRegistered())
            //ничего не делаем
            return 0;
        
        //если пользователь не существует
        if (count($user)==0)
            //создаем нового пользователя
            $user = new User;
        
        //сохраняем его данные в базе
        $user->email = $this->email;
        $user->name = $this->name;
        $user->phone = $this->phone;
        $user->company_name = $this->company_name;
        $user->role_id = Role::findOne(['alias' => 'registration_begin'])->id;
        $user->operation_key = Yii::$app->security->generateRandomString();
        
        $success = $user->save();
        
        if ($success)
        {
            //отправляем сообщение на подтверждение почты для регистрации на сайте
            Yii::$app->mailer->compose('confirmMailForRegistration',compact('user'))
                        //->setFrom('for.truck@mail.ru')
                        //->setFrom(['istiplin@gmail.com'=>'ForTruck'])
                        ->setTo($user->email)
                        ->setSubject('Заявка на регистрацию')
                        ->send();
        }
        
        return $success;

    }

}
