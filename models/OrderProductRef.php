<?php

/**
 * This is the model class for table "{{order_product_ref}}".
 *
 * The followings are the available columns in table '{{order_product_ref}}':
 * @property integer $id
 * @property integer $order_id
 * @property integer $product_id
 * @property string $product_name
 * @property string $product_price
 * @property integer $quantity
 * @property string $final_price
 * @property string $product_tax
 *
 * The followings are the available model relations:
 * @property Order $order
 * @property Product $product
 */
class OrderProductRef extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return OrderProductRef the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{order_product_ref}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('order_id, product_id, product_name, quantity', 'required'),
			array('order_id, product_id, quantity', 'numerical', 'integerOnly'=>true),
			array('product_name', 'length', 'max'=>255),
			array('product_price, final_price', 'length', 'max'=>15),
			array('product_tax', 'length', 'max'=>7),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, order_id, product_id, product_name, product_price, quantity, final_price, product_tax', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'order' => array(self::BELONGS_TO, 'Order', 'order_id'),
			'product' => array(self::BELONGS_TO, 'Product', 'product_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'order_id' => 'Order',
			'product_id' => 'Product',
			'product_name' => 'Product Name',
			'product_price' => 'Product Price',
			'quantity' => 'Quantity',
			'final_price' => 'Final Price',
			'product_tax' => 'Product Tax',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('order_id',$this->order_id);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('product_name',$this->product_name,true);
		$criteria->compare('product_price',$this->product_price,true);
		$criteria->compare('quantity',$this->quantity);
		$criteria->compare('final_price',$this->final_price,true);
		$criteria->compare('product_tax',$this->product_tax,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}