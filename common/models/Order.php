<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\Html;
use yii\helpers\Url;

use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property string $created_at
 * @property string $updated_at
 * @property int $is_complete
 * @property string $complete_time
 * @property string $user_name
 * @property string $email
 * @property string $phone
 * @property int $user_id
 * @property string $comment
 *
 * @property User $user
 * @property OrderItem[] $orderItems
 * @property Product[] $products
 */ 
class Order extends ActiveRecord
{
    public $price_sum;
    public $count;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'is_complete'], 'required'],
            [['created_at', 'updated_at', 'complete_time'], 'safe'],
            [['is_complete', 'user_id'], 'integer'],
            [['comment'], 'string'],
            [['user_name', 'email', 'phone'], 'string', 'max' => 30],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ]; 
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Номер заказа',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
            'is_complete' => 'Заказ завершен',
            'complete_time' => 'Дата завершения',
            'statusName' => 'Статус заказа',
            'user_name' => 'User Name',
            'userName' => 'Имя покупателя',
            'userPhone' => 'Телефон',
            'email' => 'Email',
            'phone' => 'Phone',
            'user_id' => 'User ID',
            'price_sum' => 'Сумма, руб.',
            'count' => 'Колчество заказов',
            'comment' => 'Комментарий к заказу',
            'orderLinkHtml' => 'Просмотр',
            'orderItemLinkHtml' => 'Посмотреть товары'
        ];
    }

    public static function getIsNotComplete()
    {
        return static::findByCondition(['is_complete'=>0])->count();
    }
    
    public function __set($name,$value)
    {
        if ($name === 'is_complete')
        {
            if($value)
                $this->complete_time = new Expression('NOW()');
            else
                $this->complete_time = null;
        }
        parent::__set ($name, $value);
    }
    
    public function getStatusList()
    {
        return [
            //''=>'Все заказы',
            0=>'Незавершенные заказы',
            1=>'Завершенные заказы'];
    }
    
    public function getStatusName()
    {
        return $this->is_complete?'Завершен':'Не завершен';
    }
    
    public function getStatusesName()
    {
        return $this->statusList[$this->is_complete];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->inverseOf('orders');
    }
    
    public function getUserName()
    {
        if (strlen($this->user_id))
            return $this->user->name;
        return $this->user_name;
    }
    
    public function getUserPhone()
    {
        if (strlen($this->user_id))
            return $this->user->phone;
        return $this->phone;
    }
    public function getOrderLinkHtml()
    {
        //если заказов больше одного
        if($this->count>1)
        {
            //создаем ссылку на список заказов
            $url = Url::to(['index','user_id'=>$this->user_id,'is_complete'=>$this->is_complete]);
            return Html::a('Посмотреть заказы', $url);
        }
        //иначе если заказ один
        else
        {
            //создаем ссылку на список товаров данного заказа
            $url = Url::to(['order-item/index','order_id'=>$this->id]);
            return Html::a('Посмотреть товары', $url);
        }
    }
    
    public function getOrderItemLinkHtml()
    {
        $url = Url::to(['order-item/index','order_id'=>$this->id]);
        return Html::a('Посмотреть товары', $url);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::className(), ['order_id' => 'id'])->inverseOf('order');
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])->viaTable('order_item', ['order_id' => 'id']);
    } 
    
    //формирует заказ по корзине
    public function form()
    {
        $cart = \frontend\models\cart\Cart::initial();
        if ($cart->getTypeCount()==0)
            return false;
        
        //получаем информацию о товарах
        $prices = $cart->getProductsInfo();
        
        //если товаров нет, заказ не оформляем
        if (count($prices)==0)
            return 0;
        
        $transaction = \Yii::$app->db->beginTransaction();
        
        $this->created_at = new Expression('NOW()');
        $this->updated_at = new Expression('NOW()');
        $this->is_complete = 0;
        $this->user_id = Yii::$app->user->identity->id;
        $this->save();
        
        $prodDelIdList=[];
        foreach($prices as $info)
        {
            if (!$info['count'])
                continue;
                
            $orderItem = new OrderItem;
            $orderItem->order_id = $this->id;
            $orderItem->product_id = $info['id'];
            $orderItem->count = $cart->getCount($info['id']);
            $orderItem->price = $info['custPrice'];
            $orderItem->save();
            
            $prodDelIdList[] = $info['id'];
        }
        
        $cart->clear($prodDelIdList);
        
        try{
            $mail = Yii::$app->mailer->compose('orderForm')
                    ->setTo(Yii::$app->mailer->transport->getUserName())
                    ->setSubject('ForTruck. Покупка товара');
            
            if ($mail->send())
            {
                $transaction->commit();
                return $this->id;
            }
            else
            {
                $transaction->rollback();
                return 0;
            }
        }
        catch(\Swift_TransportException  $e)
        {
            $transaction->rollback();
            return 0;
        }
    }
    
    public function getDataProvider()
    {
        $query = self::find()->select('order.*,sum(order_item.price*order_item.count) as price_sum')
                            ->joinWith('orderItems')
                            ->groupBy('order_item.order_id')
                            ->orderBy('order.id desc');
        
        $query->andFilterWhere([
            'user_id' => Yii::$app->user->identity->id,
        ]);
        // add conditions that should always apply here

     
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            /*
            'pagination' =>[
                'pageSize' => 2,
                'pageParam' => 'order',
            ]
             * 
             */
        ]);
        
        return $dataProvider;
    }

}
