<?php
namespace frontend\models\productSearch;

use Yii;
use yii\data\SqlDataProvider;
use yii\helpers\Html;
use frontend\models\bag\Bag;

//класс для поиска продуктов по продукту-аналогу
class AnalogProductSearch extends ProductSearch
{
    private $_productInfo;
    
    public function __construct($productInfo,Bag $bag=null)
    {
        $this->_bag = $bag;
        $this->_productInfo = $productInfo;
        $this->title = "<h3>Аналоги для <b>{$this->_productInfo['analogName']}</b>:</h3>";
    }
    
    public function getProductInfo()
    {
        return $this->_productInfo;
    }
    
    public function getDataProvider()
    {
        
        if (Yii::$app->user->isGuest)
        {
            $selectCount = "";
            $joinBag = "";
            
            $where = " where p.analog_id={$this->_productInfo['analogId']} 
                            and p.id<>{$this->_productInfo['id']} ";
        }
        else
        {
            $selectCount = ",b.count ";
            $joinBag = " left join bag b on b.product_id = p.id ";
            $where = " where p.analog_id={$this->_productInfo['analogId']} 
                            and p.id<>{$this->_productInfo['id']} 
                            and b.user_id=".Yii::$app->user->identity->id;
        }
        
        $sql = "select
                    p.id,
                    p.number,
                    p.price,
                    p.name as productName,
                    pr.name as producerName,
                    a.name as analogName
                    $selectCount
                from product p
                join analog a on a.id = p.analog_id
                join producer pr on pr.id = p.producer_id
                $joinBag
                $where";

        $count = Yii::$app->db->createCommand("select 
                                                    count(*)
                                                from product
                                                where analog_id=:analog_id and id<>:id",
                    [   
                        ':analog_id'=>$this->_productInfo['analogId'],
                        ':id'=>$this->_productInfo['id']
                    ]
                )->queryScalar();
                
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => $count,
            //'pagination' =>[
            //    'pageSize' => 2,
            //]
        ]);
        
        return $dataProvider;
    }
    
    public function getColumns()
    {
        $bag = $this->_bag;
        return [
            'number:text:Артикул',
            [
                'label'=>'Наименование',
                'value'=>function($data){
                    if (strlen($data['productName']))
                        return $data['productName'];
                    else
                        return $data['analogName'];
                },
            ],
            'producerName:text:Производитель',
            'price:text:Цена', 
            [
                'label'=>'Корзина',
                'value'=>function($data) use ($bag){
                    
                    if (Yii::$app->user->isGuest)
                        $count = $bag->count($data['id']);
                    else
                        $count = $data['count'] ?? 0;
                        
                    return Html::beginForm('', 'post', ['class' => 'add-to-bag']).
                                Html::hiddenInput('bag[id]', $data['id']).
                                Html::input('text', 'bag[count]', $count,['size'=>1]).
                                Html::submitButton('В корзину').
                            Html::endForm().
                            $bag->message($data['id']);
                },
                'format'=>'raw',
            ]
        ];
    }
}
?>
