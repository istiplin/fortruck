<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    public static $passwordCharsMaxCount = 10;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    /*
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
*/
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'name', 'phone'], 'required'],
            [['role_id'], 'integer'],
            ['email','checkEmail'],
            [['email', 'password', 'auth_key', 'operation_key'], 'string', 'max' => 255],
            [['email'], 'match', 'pattern'=>'/^[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}$/i','message'=>'Адрес электронной почты введен в неправильном формате'],
            //[['email'], 'match', 'pattern'=>'/^[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}$/i'],
            [['name'], 'string', 'max' => 100],
            [['phone'], 'string', 'max' => 30],
            [['company_name'], 'string', 'max' => 50],
            [['email'], 'unique'],
            [['auth_key'], 'unique'],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::className(), 'targetAttribute' => ['role_id' => 'id']],
        ];
    }

    public function checkEmail($attribute)
    {
        $user = self::findIdentity($this->id);
        if (strlen($user->email) AND $user->email!==$this->$attribute)
        {
            $this->$attribute = $user->email;
            $this->addError($attribute,'Нельзя изменить почту');
        }
    }
    
    /**
     * {@inheritdoc}
     */
    
    public function attributeLabels()
    {
        return [
            'id' => 'ИД пользователя',
            'email' => 'Email',
            'password' => 'Password',
            'auth_key' => 'Auth Key',
            'operation_key' => 'Operation Key',
            'name' => 'Имя',
            'phone' => 'Телефон',
            'company_name' => 'Организация',
            //'role_id' => 'Статус',
            'roleName'=>'Статус', //----------------------------
        ];
    }
    
    /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getOrders() 
    { 
        return $this->hasMany(Order::className(), ['user_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::className(), ['id' => 'role_id']);
    }
    
    //-----------------------------
    public function getRoleName()
    {
        return $this->role->name;
    }
    
    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }
    
    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
    
    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findByEmail($username);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        if ($this->password===$password)
            return true;
        elseif(strlen($this->password)>self::$passwordCharsMaxCount)
            return Yii::$app->security->validatePassword($password, $this->password);
        return false;
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        //$this->password_hash = Yii::$app->security->generatePasswordHash($password);
        $this->password = $password;
    }
    
    public function generatePassword()
    {
        $password = Yii::$app->security->generateRandomString(self::$passwordCharsMaxCount);
        //Убираем символы "тире" и "нижнее подчеркивание"
        $password = str_replace(['_','-'],'',$password);
        return $password;
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
            
            $success = $this->save();
            
            if ($success)
            {
                Yii::$app->mailer->compose('newPasswordForRegistration',['password'=>$password, 'user'=>$this])
                        ->setTo($this->email)
                        ->setSubject('ForTruck. Заявка на регистрацию')
                        ->send();
            }
            
            return $success;
            
            //return $this->save();
        }
        return false;
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    
    //подтверждает регистрацию
    public function confirmRegistration($operation_key)
    {
        if ($this->role->alias==='registration_begin' && $this->operation_key===$operation_key)
        {
            $this->role_id = Role::getIdByAlias('mail_confirmed');
            $this->operation_key = null;
            $success = $this->save();
            
            if ($success)
            {
                Yii::$app->mailer->compose('registrationRequest',['user'=>$this])
                            ->setTo(Yii::$app->mailer->transport->getUserName())
                            ->setSubject('Заявка на регистрацию')
                            ->send();
            }
            
            return $success;
        }
        return false;
    }
    
    //устанавливает пользователю ключ для изменения пароля
    public function setOperationKeyForChangePassword()
    {
        if ($this->isRegistered())
        {
            $this->operation_key = Yii::$app->security->generateRandomString();
            $success = $this->save();
            
            if ($success)
            {
                //отправляем сообщение на подтверждение почты для смены пароля
                Yii::$app->mailer->compose('confirmMailForChangePassword',['user'=>$this])
                        ->setTo($this->email)
                        ->setSubject('ForTruck. Восстановление пароля')
                        ->send();
            }
            
            return $success;
        }
        return false;
    }
    
    //подтверждает изменение пароля
    public function confirmChangePassword($operation_key,$password=null)
    {
        if($this->isRegistered() && $this->operation_key===$operation_key)
        {   
            if ($password===null)
                $password = $this->generatePassword();
            $this->setPassword($password);   
            $this->operation_key = null;
            
            $success = $this->save();
            
            if ($success)
            {
                Yii::$app->mailer->compose('newPasswordForChangePassword',compact('password'))
                        ->setTo($this->email)
                        ->setSubject('ForTruck. Восстановление пароля')
                        ->send();
            }
            
            return $success;
        }
        return false;
    }
    
    public function isAdmin()
    {
        return $this->role->alias=='vendor';
    }
    
    public function isRegistered()
    {
        return $this->role->alias!='registration_begin' && $this->role->alias!='mail_confirmed';
    }
    
    public function getRoles()
    {
        if ($this->isRegistered())
            return Role::find()->select('name,id')->where("alias not in('registration_begin','mail_confirmed')")->indexBy('id')->asArray()->column();
        else
            return Role::find()->select('name,id')->where("alias in('{$this->role->alias}')")->indexBy('id')->asArray()->column();
    }

}
