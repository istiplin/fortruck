<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

use yii\data\ActiveDataProvider;

use frontend\models\BagModel;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property string $created_at
 * @property string $updated_at
 * @property int $status
 * @property string $user_name
 * @property string $email
 * @property string $phone
 * @property int $user_id
 *
 * @property User $user
 * @property OrderItem[] $orderItems
 */
class Order extends ActiveRecord
{
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
            [['is_complete'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['is_complete', 'user_id'], 'integer'],
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
            'user_name' => 'User Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'user_id' => 'User ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::className(), ['order_id' => 'id']);
    }
    
    //формирует заказ по корзине
    public function form($bag)
    {
        $this->is_complete = 0;
        $this->user_id = Yii::$app->user->identity->id;
        $this->save();

        $prices = $bag->getProductsInfo();

        foreach($prices as $id=>$info)
        {
            $orderItem = new OrderItem;
            $orderItem->order_id = $this->id;
            $orderItem->product_id = $info['id'];
            $orderItem->count = $info['count'];
            $orderItem->price = $info['price'];
            $orderItem->save();
        }
        
        Yii::$app->mailer->compose('orderForm')
                ->setTo(Yii::$app->mailer->transport->getUserName())
                ->setSubject('ForTruck. Покупка товара')
                ->send();
        
        $bag->clear();
    }
    
    public function getDataProvider()
    {
        $query = self::find()->orderBy('updated_at desc');
        
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
