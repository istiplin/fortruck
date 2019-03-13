<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Product;
use common\models\Brand;
use common\models\OriginalProduct;
use frontend\models\Product as CustProduct;

/**
 * ProductSearch represents the model behind the search form of `common\models\Product`.
 */
class ProductSearch extends Product
{
    public $originalName;
    public $brandName;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'original_id', 'count', 'is_visible'], 'integer'],
            [['price_change_time', 'name', 'number', 'originalName', 'brandName'], 'trim'],
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
                                                'originalProduct'=>function($q){
                                                    $q->from(['originalProduct'=>OriginalProduct::tableName()]);
                                                },
                                                'brand'=>function($q){
                                                    $q->from(['brand'=>Brand::tableName()]);
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
                    'originalName'=>[
                            'asc'=>['originalProduct.name'=>SORT_ASC],
                            'desc'=>['originalProduct.name'=>SORT_DESC],
                    ],
                    'brandName'=>[
                            'asc'=>['brand.name'=>SORT_ASC],
                            'desc'=>['brand.name'=>SORT_DESC],
                    ],
                ]
            )
        ]);
        
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere(['like', 'product.number', $this->number])
            ->andFilterWhere(['like', 'product.name', $this->name]);
        
        $query->andFilterWhere(['like', 'originalProduct.name', $this->originalName]);
        $query->andFilterWhere(['like', 'brand.name', $this->brandName]);
        $query->andFilterWhere(['product.is_visible' => $this->is_visible]);
        
        
        $models = $dataProvider->getModels();
        foreach ($models as $model)
        {
            $custProduct = new CustProduct(['price'=>$model->price]);
            $model->custPrice = $custProduct->custPrice;
        }
        $dataProvider->setModels($models);
        
        
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
            ->andFilterWhere(['like', 'product.name', $this->name]);
        
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
