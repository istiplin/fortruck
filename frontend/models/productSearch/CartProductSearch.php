<?php
namespace frontend\models\productSearch;

use Yii;
use yii\data\ArrayDataProvider;
use yii\data\SqlDataProvider;
use frontend\models\cart\Cart;
use frontend\models\Product;

//класс для вывода продуктов, которые в корзине
class CartProductSearch extends \yii\base\Component
{
    public $title;
    private $_dataProvider;
    
    public function __construct()
    {
        $this->title = "<h3>Корзина:</h3>";
    }
    
    public function getDataProvider()
    {
        if ($this->_dataProvider!==null)
            return $this->_dataProvider;
        
        if (Cart::initial()->typeCount==0)
            return $this->_dataProvider = new ArrayDataProvider;
        
        $implodedId = implode(',',Cart::initial()->listId);
        
        $sql = 'select
                    p.id,
                    p.number,
                    p.norm_number,
                    b.name as brandName,
                    p.name,
                    p.price,
                    p.count
                from product p
                left join brand b on b.id = p.brand_id
                where p.id in('.$implodedId.') and p.price>0
                order by p.count desc';

        $sqlCount = 'select count(*)
                    from product
                    where id in('.$implodedId.') and price>0';

        $count = Yii::$app->db->createCommand($sqlCount)->queryScalar();

        $provider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => $count,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        
        $models = $provider->getModels();

        foreach ($models as &$model)
            $model = new Product($model);

        $provider->setModels($models);
        
        return $this->_dataProvider = $provider;
    }
    
    public function getColumns()
    {
        return [
            'number',
            [
                'attribute'=>'brandName',
                'headerOptions'=>['class'=>'producer-field-header'],
                'contentOptions'=>['class'=>'producer-field'],
            ],
            'name',
            [
                'attribute'=>'custPriceView',
                'value'=>function($data){return $data->getCustPriceView(false);},
                'format'=>'raw',
                'headerOptions'=>['class'=>'price-field-header'],
                'contentOptions'=>['class'=>'price-field'],
            ],
            [
                'attribute'=>'cartCountView',
                'value'=>function($data){return $data->getCartCountView(false);},
                'format'=>'raw',
                'headerOptions'=>['class'=>'cart-count-field'],
                'contentOptions'=>['class'=>'cart-count-field'],
                'visible'=>!Yii::$app->user->isGuest
            ],        
            [
                'attribute'=>'cartView',
                'value'=>function($data){return $data->getCartView(false);},
                'format'=>'raw',
                'headerOptions'=>['class'=>'order-field'],
                'contentOptions'=>['class'=>'order-field'],
                'visible'=>!Yii::$app->user->isGuest
            ]        
        ];
    }
    
    public function getRowOptions(){
        return function($data){
            return ['data-norm-number'=>mb_strtoupper($data->norm_number),
                    'data-brand'=>mb_strtoupper($data->brandName), 
                    'class'=>'product-data '.($data->isAvailable?'':'not-available')];};
    }
    
    public function getItemOptions(){
        return function($data){
            return ['data-norm-number'=>mb_strtoupper($data->norm_number),
                    'data-brand'=>mb_strtoupper($data->brandName), 
                    'tag' => 'div',
                    'class'=>'product-data'.($data->isAvailable?'':' not-available')];};
    }
    
    public function getAttributeLabel($attribute)
    {
        return $this->_dataProvider->models[0]->getAttributeLabel($attribute);
    }
}
?>
