<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
 * UserSearch represents the model behind the search form of `common\models\User`.
 */
class UserSearch extends User
{
    public $roleName;//---------------------------------------------
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'role_id'], 'integer'],
            [['email', 'password', 'auth_key', 'operation_key', 'name', 'mobile', 'company_name'], 'safe'],
            [['roleName'], 'safe'],//------------------------------------
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
        $query = User::find()->joinWith(['role']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' =>[
                //'pageSize' => 2,
            ]
        ]);
        
        //----------------------------------------------------------------------
        $dataProvider->setSort([
            'attributes' => array_merge($dataProvider->getSort()->attributes,
                [
                    'roleName'=>[
                        'asc'=>['role.name'=>SORT_ASC],
                        'desc'=>['role.name'=>SORT_DESC],
                    ]
                ]
            )
        ]);
        
        
        $this->load($params);
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'role_id' => $this->role_id,
        ]);

        $query->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'operation_key', $this->operation_key])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'company_name', $this->company_name]);
        
        //-------------------------------------------------------------------------
        $query->andFilterWhere(['like', 'role.name', $this->roleName]);
/*
        if (strlen($this->roleName))
            $query->joinWith(['role'=>function($q){
                $q->where('role.name LIKE "%'.$this->roleName.'%"');
            }]);
  */        
                
        
        return $dataProvider;
    }
}
