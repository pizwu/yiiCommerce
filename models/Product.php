<?php

/**
 * This is the model class for table "tbl_product".
 *
 * The followings are the available columns in table 'tbl_product':
 * @property integer $id
 * @property string $name
 * @property string $sku
 * @property integer $quantity
 * @property string $model
 * @property string $price
 * @property integer $date_added
 * @property integer $last_modified
 * @property integer $date_available
 * @property string $weight
 * @property integer $status
 * @property integer $tax_class_id
 * @property integer $manufacturer_id
 * @property string $description
 * @property string $url
 * @property integer $viewed
 * @property integer $ordered
 *
 * The followings are the available model relations:
 * @property Manufacturer $manufacturer
 * @property ProductAttributeValue[] $productAttributeValues
 * @property ProductCategoryRef[] $productCategoryRefs
 * @property ProductImageRef[] $productImageRefs
 * @property ProductMultilingual[] $productMultilinguals
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
		return 'tbl_product';
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
			array('sku', 'length', 'max'=>128),
			array('model', 'length', 'max'=>12),
			array('price', 'length', 'max'=>15),
			array('weight', 'length', 'max'=>5),
			array('url', 'length', 'max'=>255),
			array('description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, sku, quantity, model, price, date_added, last_modified, date_available, weight, status, tax_class_id, manufacturer_id, description, url, viewed, ordered', 'safe', 'on'=>'search'),
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
			'manufacturer' => array(self::BELONGS_TO, 'Manufacturer', 'manufacturer_id'),
			'productAttributeValues' => array(self::HAS_MANY, 'ProductAttributeValue', 'product_id'),
			'productCategoryRefs' => array(self::HAS_MANY, 'ProductCategoryRef', 'product_id'),
			'productImageRefs' => array(self::HAS_MANY, 'ProductImageRef', 'product_id'),
			'productMultilinguals' => array(self::HAS_MANY, 'ProductMultilingual', 'product_id'),
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
			'sku' => 'Sku',
			'quantity' => 'Quantity',
			'model' => 'Model',
			'price' => 'Price',
			'date_added' => 'Date Added',
			'last_modified' => 'Last Modified',
			'date_available' => 'Date Available',
			'weight' => 'Weight',
			'status' => 'Status',
			'tax_class_id' => 'Tax Class',
			'manufacturer_id' => 'Manufacturer',
			'description' => 'Description',
			'url' => 'Url',
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
		$criteria->compare('sku',$this->sku,true);
		$criteria->compare('quantity',$this->quantity);
		$criteria->compare('model',$this->model,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('date_added',$this->date_added);
		$criteria->compare('last_modified',$this->last_modified);
		$criteria->compare('date_available',$this->date_available);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('tax_class_id',$this->tax_class_id);
		$criteria->compare('manufacturer_id',$this->manufacturer_id);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('url',$this->url,true);
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
}