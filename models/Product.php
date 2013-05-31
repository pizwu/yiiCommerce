<?php

/**
 * This is the model class for table "{{product}}".
 *
 * The followings are the available columns in table '{{product}}':
 * @property integer $id
 * @property string $name
 * @property string $sn
 * @property string $upc
 * @property integer $quantity
 * @property string $model
 * @property string $price
 * @property integer $date_added
 * @property integer $last_modified
 * @property integer $date_available
 * @property integer $status
 * @property integer $tax_class_id
 * @property integer $manufacturer_id
 * @property string $description
 * @property integer $viewed
 * @property integer $ordered
 *
 * The followings are the available model relations:
 * @property OrderProductRef[] $orderProductRefs
 * @property Manufacturer $manufacturer
 * @property ProductAttributeValue[] $productAttributeValues
 * @property ProductCategoryRef[] $productCategoryRefs
 * @property ProductImageRef[] $productImageRefs
 * @property ProductMultilingual[] $productMultilinguals
 * @property ProductSeo[] $productSeos
 * @property ProductSpecRef[] $productSpecRefs
 * @property ProductTagRef[] $productTagRefs
 * @property ShoppingCart[] $shoppingCarts
 */
class Product extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Product the static model class
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
		return '{{product}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, date_added, status', 'required'),
			array('quantity, date_added, last_modified, date_available, status, tax_class_id, manufacturer_id, viewed, ordered', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>64),
			array('sn, upc', 'length', 'max'=>128),
			array('model', 'length', 'max'=>12),
			array('price', 'length', 'max'=>15),
			array('description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, sn, upc, quantity, model, price, date_added, last_modified, date_available, status, tax_class_id, manufacturer_id, description, viewed, ordered', 'safe', 'on'=>'search'),
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
			'orderProductRefs' => array(self::HAS_MANY, 'OrderProductRef', 'product_id'),
			'manufacturer' => array(self::BELONGS_TO, 'Manufacturer', 'manufacturer_id'),
			'productAttributeValues' => array(self::HAS_MANY, 'ProductAttributeValue', 'product_id'),
			'productCategoryRefs' => array(self::HAS_MANY, 'ProductCategoryRef', 'product_id'),
			'productImageRefs' => array(self::HAS_MANY, 'ProductImageRef', 'product_id'),
			'productMultilinguals' => array(self::HAS_MANY, 'ProductMultilingual', 'product_id'),
			'productSeos' => array(self::HAS_MANY, 'ProductSeo', 'product_id'),
			'productSpecRefs' => array(self::HAS_MANY, 'ProductSpecRef', 'product_id'),
			'productTagRefs' => array(self::HAS_MANY, 'ProductTagRef', 'product_id'),
			'shoppingCarts' => array(self::HAS_MANY, 'ShoppingCart', 'product_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'sn' => 'Sn',
			'upc' => 'Upc',
			'quantity' => 'Quantity',
			'model' => 'Model',
			'price' => 'Price',
			'date_added' => 'Date Added',
			'last_modified' => 'Last Modified',
			'date_available' => 'Date Available',
			'status' => 'Status',
			'tax_class_id' => 'Tax Class',
			'manufacturer_id' => 'Manufacturer',
			'description' => 'Description',
			'viewed' => 'Viewed',
			'ordered' => 'Ordered',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('sn',$this->sn,true);
		$criteria->compare('upc',$this->upc,true);
		$criteria->compare('quantity',$this->quantity);
		$criteria->compare('model',$this->model,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('date_added',$this->date_added);
		$criteria->compare('last_modified',$this->last_modified);
		$criteria->compare('date_available',$this->date_available);
		$criteria->compare('status',$this->status);
		$criteria->compare('tax_class_id',$this->tax_class_id);
		$criteria->compare('manufacturer_id',$this->manufacturer_id);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('viewed',$this->viewed);
		$criteria->compare('ordered',$this->ordered);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * Before validate: transfer date_available mm/dd/yyyy to timestamp
	 */
	public function beforeValidate()
	{
		if(gettype($this->date_available) == 'string'){
			$this->date_available = strtotime($this->date_available);
		}
		
		return parent::beforeValidate();
	}
	
	/**
	 * After find: transfer date_available timestamp to mm/dd/yyyy
	 */
	public function afterFind()
	{
		$this->date_available = date('m/d/Y', $this->date_available);
		
		return parent::afterFind();
	}
	
	/**
	 * Combination of tags, into string
	 */
	public function tags()
	{
		$tags = array();
		foreach ($this->productTagRefs as $key => $ref) {
			$tags[] = $ref->tag->name;
		}
		
		return implode(', ', $tags);
	}
}