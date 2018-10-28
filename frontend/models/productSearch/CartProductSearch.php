<?php
namespace frontend\models\productSearch;

use Yii;
use yii\data\ArrayDataProvider;
use yii\data\SqlDataProvider;

//класс для вывода продуктов, которые в корзине
class CartProductSearch extends ProductSearch
{
    public function __construct()
    {
        parent::init();
        $this->title = "<h3>Корзина:</h3>";
    }
    
    public function getDataProvider()
    {
        if ($this->cart->typeCount==0)
            return new ArrayDataProvider;
        
        $implodedId = implode(',',$this->cart->listId);
        $sql = "select *, price
                from product
                where id in($implodedId)";

        $dataProvider = new SqlDataProvider([
           'sql' => $sql,
           'totalCount' => $this->cart->typeCount,
           'pagination' => false,
        ]);
        
        return $dataProvider;
    }
}
?>
