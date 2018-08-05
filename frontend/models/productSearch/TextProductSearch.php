<?php
namespace frontend\models\productSearch;

use Yii;
use yii\data\SqlDataProvider;
use yii\helpers\Html;
use frontend\models\bag\Bag;

//класс для поиска продуктов по тексту
class TextProductSearch extends ProductSearch
{
    private $_text;
    
    public function __construct($text,Bag $bag=null)
    {
        $this->_bag = $bag;
        $this->_text = $text;
        $this->title='';
        if (strlen($this->_text))
            $this->title = "<h4>Результаты поиска по запросу <b>'{$this->_text}'</b></h4>";
    }
    
    public function getDataProvider()
    {
        $selectCount = "";
        $joinBag = "";
        $andWhere = "";

        if (!Yii::$app->user->isGuest)
        {
            $selectCount = ",b.count ";
            $joinBag = " left join bag b on b.product_id = p.id ";
            $andWhere = " and (b.user_id is null or b.user_id=".Yii::$app->user->identity->id.")";
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
                where (p.number like :text or p.name like :text or a.name like :text) 
                $andWhere";

        $sqlCount = "select 
                        count(*)
                    from product p
                    join analog a on a.id = p.analog_id
                    where p.number like :text or p.name like :text or a.name like :text";
        
        $count = Yii::$app->db->createCommand($sqlCount,[':text'=>"%{$this->_text}%"])->queryScalar();
                
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'params' => [':text'=>"%{$this->_text}%"],
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
            [
                'label'=>'Артикул',
                'value'=>function($data){
                    return Html::a($data['number'],['site/search','text'=>$data['number']],['title'=>'Посмотреть аналоги']);
                },
                'format'=>'raw',
            ],
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
