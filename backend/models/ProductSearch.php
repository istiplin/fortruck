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
    public $originalNumber;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'original_id', 'count', 'is_visible'], 'integer'],
            [['number', 'name', 'producer_name', 'price_change_time', 'originalNumber','is_visible'], 'safe'],
            [['price'], 'number'],
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
        $query = Product::find()->joinWith([
                                                'original'=>function($q){
                                                    $q->from(['original'=>Product::tableName()]);
                                                }
                                        ]);
                                //->orderBy('original.number asc, abs(product.id-product.original_id) asc');
        if (!isset($params['sort']))
            $query->orderBy('product.price_change_time asc');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' =>[
                'pageSize' => 10,
            ],
            //'sort' => false,
        ]);

        $dataProvider->setSort([
            'attributes' => array_merge($dataProvider->getSort()->attributes,
                    [
                        'originalNumber'=>[
                            'asc'=>['original.number'=>SORT_ASC, 'abs(product.id-product.original_id)'=>SORT_ASC],
                            'desc'=>['original.number'=>SORT_DESC, 'abs(product.id-product.original_id)'=>SORT_ASC],
                    ],
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
        $query->andFilterWhere(['like', 'product.number', $this->number])
            ->andFilterWhere(['like', 'product.name', $this->name])
            ->andFilterWhere(['like', 'producer_name', $this->producer_name]);
        
        $query->andFilterWhere(['like', 'original.number', $this->originalNumber]);
        $query->andFilterWhere(['product.is_visible' => $this->is_visible]);
                
        /*
        if (strlen($this->originalNumber))
            $query->andWhere([
                            'OR',
                            [
                                'AND',
                                ['like','product.number',$this->originalNumber],
                                ['=','product.original_id',0]
                            ],
                            [
                                'AND',
                                ['like','original.number',$this->originalNumber],
                                ['<>','product.original_id',0]
                            ]
                        ]);
        */
        
        return $dataProvider;
    }
    
    public function searchPrice($params)
    {
        $query = Product::find()
                        ->select([
                            'id',
                            'number',
                            'price',
                            'price_change_time'
                        ]);
        
        if (!isset($params['sort']))
            $query->orderBy('price_change_time desc');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' =>[
                'pageSize' => 10,
            ],
            'sort'=>false
        ]);

        $dataProvider->setSort([
            'attributes' => //array_merge($dataProvider->getSort()->attributes,
                [
                    'price_change_time'=>[
                        'asc'=>['price_change_time'=>SORT_ASC],
                        'desc'=>['price_change_time'=>SORT_DESC],
                    ],
                ]
            //)
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        /*
        $query->andFilterWhere(['like', 'product.number', $this->number])
            ->andFilterWhere(['like', 'product.name', $this->name])
            ->andFilterWhere(['like', 'producer_name', $this->producer_name]);
        
        $query->andFilterWhere(['like', 'original.number', $this->originalNumber]);
         * 
         */
                
        /*
        if (strlen($this->originalNumber))
            $query->andWhere([
                            'OR',
                            [
                                'AND',
                                ['like','product.number',$this->originalNumber],
                                ['=','product.original_id',0]
                            ],
                            [
                                'AND',
                                ['like','original.number',$this->originalNumber],
                                ['<>','product.original_id',0]
                            ]
                        ]);
        */
        
        return $dataProvider;
    }
}
