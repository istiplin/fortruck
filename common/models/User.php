<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/** 
 * This is the model class for table "user". 
 * 
 * @property int $id
 * @property string $email
 * @property string $password
 * @property string $auth_key
 * @property string $operation_key
 * @property string $name
 * @property string $phone
 * @property string $company_name
 * @property int $role_id
 * @property string $registration_data
 * 
 * @property Cart[] $carts
 * @property Product[] $products
 * @property Order[] $orders
 * @property Role $role
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
    public function rules()
    {
        return [
            [['email', 'name', 'phone'], 'required'],
            [['role_id'], 'integer'],
            [['registration_data'], 'safe'],
            ['email','checkEmail'],
            [['email', 'password', 'auth_key', 'operation_key'], 'string', 'max' => 255],
            [['email'], 'match', 'pattern'=>'/^[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}$/i','message'=>'Адрес электронной почты введен в неправильном формате'],
            [['name'], 'string', 'max' => 100],
            [['phone','email'], 'string', 'min'=>4, 'max' => 30],
            [['company_name'], 'string', 'max' => 50],
            [['email'], 'unique'],
            [['auth_key'], 'unique'],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::className(), 'targetAttribute' => ['role_id' => 'id']],
        ];
    }

    public function checkEmail($attribute)
    {
        $user = self::findIdentity($this->id);
        if ($user AND $user->email!==$this->$attribute)
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
            'role_id' => 'Статус',
            'roleName'=>'Статус', //----------------------------
            'registration_data'=>'Дата регистрации',
        ];
    }

    /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getCarts() 
    { 
        return $this->hasMany(Cart::className(), ['user_id' => 'id'])->inverseOf('user');
    } 

    /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getProducts() 
    { 
        return $this->hasMany(Product::className(), ['id' => 'product_id'])->viaTable('cart', ['user_id' => 'id']);
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
    
    public static function getMailConfirmedCount()
    {
        return static::find()->joinWith('role')->where(['role.alias'=>'mail_confirmed'])->count();
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
    
    public function isAdmin()
    {
        return $this->role->alias=='vendor';
    }
    
    public function isRegistered()
    {
        return strlen($this->role_id) && $this->role->alias!=='registration_begin' && $this->role->alias!=='mail_confirmed';
    }
    
    public function getRoles()
    {
        if ($this->isRegistered())
            return Role::find()->select('name,id')->where("alias not in('registration_begin')")->indexBy('id')->asArray()->column();
        else
            return Role::find()->select('name,id')->where("alias in('{$this->role->alias}')")->indexBy('id')->asArray()->column();
    }
}
