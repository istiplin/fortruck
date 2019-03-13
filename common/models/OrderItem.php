<?php

namespace common\models;

use Yii;

use frontend\models\Product as CustProduct;

/**
 * This is the model class for table "order_item".
 *
 * @property int $order_id
 * @property int $product_id
 * @property string $price
 * @property int $count
 *
 * @property Order $order
 * @property Product $product
 */
class OrderItem extends \yii\db\ActiveRecord
{
    //артикул товара
    private $_productNumber;
    
    //комментарий к заказу
    private $_comment;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'product_id', 'price', 'count'], 'required'],
            [['order_id', 'product_id', 'count'], 'integer'],
            [['price'], 'number'],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['productNumber', 'comment'],'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'Order ID',
            'product_id' => 'Артикул',
            'price' => 'Цена товара при заказе',
            'count' => 'Количество',
            'productNumber' => 'Артикул',
            'comment' => 'Комментарий к заказу'
        ];
    }

    public function getProductNumber()
    {
        if (isset($this->_productNumber))
            return $this->_productNumber;
        
        return $this->product->number;
    }
    
    public function getComment()
    {
        if (isset($this->_comment))
            return $this->_comment;
        
        return $this->order->comment;
    }
    
    public function beforeValidate() {
        if (parent::beforeValidate())
        {
            //если было установлено значение артикла
            if (isset($this->_productNumber))
            {
                $productNumber = $this->_productNumber;
                
                //определяем его id
                $product_id = Product::findOne(['number' => $productNumber])->id;

                //если id определен
                if ($product_id)
                {
                    //сохраняем id
                    $this->product_id = $product_id;
                    return true;
                }

                $this->addError('productNumber',"Такой артикул не существует");
                return false;

            }
        }
        return true;
    }
    
    public function save($runValidation = true, $attributeNames = null)
    {
        if ($runValidation AND $this->hasErrors())
            return false;
        return parent::save($runValidation,$attributeNames);
    }
    
    public function __set($name,$value)
    {
        if ($name === 'productNumber')
            $this->_productNumber = $value;
        elseif($name === 'product_id')
        {
            parent::__set($name, $value);
            
            $product = Product::findOne(['id' => $value]);
            
            $custProduct = new CustProduct(['price'=>$product->price,'count'=>$product->count]);
            if ($custProduct->isPresent)
                parent::__set('price', $product->price);
            else
                $this->addError('product_id',"Товар с текущим артикулом не иммеет цену либо нет в наличии");
        }
        elseif($name === 'comment')
        {
            $this->order->comment = $value;
            $this->order->save();
        }
        else
            parent::__set($name, $value);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
