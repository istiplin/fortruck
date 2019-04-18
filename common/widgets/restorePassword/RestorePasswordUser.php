<?php
namespace common\widgets\restorePassword;

use common\models\User;

//класс пользователь, котором хранятся методы для изменения пароля
class RestorePasswordUser extends User
{
    //устанавливает пользователю ключ для изменения пароля
    public function setOperationKey()
    {
        if (!$this->isRegistered())
            return false;
        
        $this->operation_key = \Yii::$app->security->generateRandomString();
        return $this->save();
    }
    
    //отправляет пользователю сообщение для подтверждения почты, 
    //где будет ссылка для подтверждения почты $mailConfirmUrl
    public function sendMailConfirmMessage($mailConfirmUrl)
    {
        $mailConfirmUrl['id'] = $this->id;
        $mailConfirmUrl['operation_key'] = $this->operation_key;
        
        \Yii::$app->mailer
            ->compose('confirmMailForChangePassword',[
                                            'userName'=>$this->name, 
                                            'mailConfirmUrl'=>$mailConfirmUrl])
            ->setTo($this->email)
            ->setSubject('ForTruck. Восстановление пароля')
            ->send();
    }
    
    //подтверждает почту по ключу
    public function confirmMail($operation_key)
    {
        if(!$this->isRegistered())
            return false;
        
        if (strlen($operation_key)==0 OR $this->operation_key!==$operation_key)
            return false;
        
        $password = $this->generatePassword();
        $this->setPassword($password);   
        $this->operation_key = null;
        
        $transaction = \Yii::$app->db->beginTransaction();
        if (!$this->save())
        {
            $transaction->rollback();
            return false;
        }
        
        try{
            $mail = \Yii::$app->mailer
            ->compose('newPasswordForChangePassword',compact('password'))
            ->setTo($this->email)
            ->setSubject('ForTruck. Восстановление пароля');
            
            if ($mail->send())
            {
                $transaction->commit();
                return true;
            }
            else
            {
                $transaction->rollback();
                return false;
            }
        }
        catch(\Swift_TransportException  $e)
        {
            $transaction->rollback();
            return false;
        }
        
        return false;
    }
}
?>
