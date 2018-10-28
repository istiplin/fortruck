<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Order;

/**
 * OrderSearch represents the model behind the search form of `common\models\Order`.
 */
class OrderSearch extends Order
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'is_complete', 'user_id'], 'integer'],
            [['created_at', 'updated_at', 'user_name', 'email', 'phone', 'complete_time', 'comment'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }
    
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $shortName = (new \ReflectionClass($this))->getShortName();
        if (strlen($params['user_id']))
        {
            $params[$shortName]['user_id'] = $params['user_id'];
            unset($params['user_id']);
        }
        if (strlen($params['is_complete']))
        {
            $params[$shortName]['is_complete'] = $params['is_complete'];
            unset($params['is_complete']);
        }
        
        $query = Order::find()->select([
                                        'order.id',
                                        'order.user_id',
                                        'user.name',
                                        'user.phone',
                                        'order.created_at',
                                        'order.is_complete',
                                        'order.complete_time',
                                        'sum(order_item.price*order_item.count) as price_sum',
                                        'order.comment'
                                    ])
                            ->joinWith(['user','orderItems'])
                            ->groupBy('order_item.order_id');
                            //->orderBy('created_at');
        
        //если заказ завершен
        if ($params[$shortName]['is_complete'])
            //сортитруем по последней дате завершения
            $query->orderBy('complete_time desc');
        //иначе
        else
            //сортитруем по первой дате создания
            $query->orderBy('created_at');
        
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            //'order.id' => $this->id,
            //'created_at' => $this->created_at,
            //'updated_at' => $this->updated_at,
            'is_complete' => $this->is_complete,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'user_name', $this->user_name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone]);

        return $dataProvider;
    }
    
    public function usersSearch($params)
    {
        $shortName = (new \ReflectionClass($this))->getShortName();
        if (strlen($params['is_complete']))
        {
            $params[$shortName]['is_complete'] = $params['is_complete'];
            unset($params['is_complete']);
        }
        elseif (!isset($params[$shortName]) OR !array_key_exists('is_complete',$params[$shortName]))
            $params[$shortName]['is_complete'] = 0;
        
        
        $query = Order::find()->select([
                                        'order.id',
                                        'order.user_id',
                                        'user.email',
                                        'user.name',
                                        'user.phone',
                                        'min(order.created_at) as created_at',
                                        'max(order.complete_time) as complete_time',
                                        'order.is_complete',
                                        'count(*) as count'])
                            ->joinWith(['user'])
                            ->groupBy('order.user_id, order.email');
                //->orderBy('min(order.created_at)');
        
        //если заказ завершен
        if ($params[$shortName]['is_complete'])
            //сортитруем по последней дате завершения
            $query->orderBy('max(order.complete_time) desc');
        //иначе
        else
            //сортитруем по первой дате создания
            $query->orderBy('min(order.created_at)');
            
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        // grid filtering conditions
        $query->andFilterWhere([
            //'id' => $this->id,
            //'created_at' => $this->created_at,
            //'updated_at' => $this->updated_at,
            'is_complete' => $this->is_complete,
            //'user_id' => $this->user_id,
        ]);
        
        $query->andFilterWhere(['like', 'user_name', $this->user_name])
            ->andFilterWhere(['like', 'user.email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone]);

        return $dataProvider;
    }
}
