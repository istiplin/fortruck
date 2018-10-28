<?php
namespace frontend\widgets\registrationForm;

use Yii;
use yii\base\Model;
use common\models\User;
//use common\models\Role;

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
    
    public function attributeLabels() {
        return [
            'company_name'=>'Наименование организации:',
            'name'=>'Ваше имя:',
            'phone'=>'Ваш контактный телефон:',
            'email'=>'Ваш адрес электронной почты:',
        ];
    }
    
    public function registrationCheck($attribute)
    {
        $user = User::findByEmail($this->$attribute);
        //если пользователь существует и его регистрация подтверждена
        if ($user && $user->isRegistered())
            //выводим ошибку
            $this->addError($attribute,'Такой адрес уже зарегистрирован!');
    }
    
    protected function getUser()
    {
        if ($this->_user === null)
            $this->_user = User::findByEmail($this->email);
        
        if ($this->_user === null)
            $this->_user = new User;
        
        return $this->_user;
    }
    
    //сохраняет данные формы 
    public function save($mailConfirmUrl)
    {
        $this->user->attributes = $this->attributes;
        return $this->user->saveAndSendMailForConfirmRegistr($mailConfirmUrl);
    }

}
