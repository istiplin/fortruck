<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Product;

/**
 * ProductSearch represents the model behind the search form of `common\models\Product`.
 */
class ProductSearch extends Product
{
    public $analogName;
    public $producerName;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'analog_id', 'producer_id'], 'integer'],
            [['number', 'name'], 'safe'],
            [['analogName','producerName'], 'safe'],
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
        $query = Product::find()->joinWith(['analog','producer']);;

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => array_merge($dataProvider->getSort()->attributes,
                [
                    'analogName'=>[
                        'asc'=>['analog.name'=>SORT_ASC],
                        'desc'=>['analog.name'=>SORT_DESC],
                    ],
                    'producerName'=>[
                        'asc'=>['producer.name'=>SORT_ASC],
                        'desc'=>['producer.name'=>SORT_DESC],
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
            'analog_id' => $this->analog_id,
            'producer_id' => $this->producer_id,
        ]);

        $query->andFilterWhere(['like', 'number', $this->number])
            ->andFilterWhere(['like', 'name', $this->name]);

        $query->andFilterWhere(['like', 'analog.name', $this->analogName]);
        $query->andFilterWhere(['like', 'producer.name', $this->producerName]);
        
        return $dataProvider;
    }
}
