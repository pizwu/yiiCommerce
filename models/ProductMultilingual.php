<?php

/**
 * This is the model class for table "tbl_product_multilingual".
 *
 * The followings are the available columns in table 'tbl_product_multilingual':
 * @property integer $id
 * @property integer $product_id
 * @property integer $language_id
 * @property string $name
 * @property string $desc
 * @property string $url
 * @property integer $viewed
 *
 * The followings are the available model relations:
 * @property Product $product
 * @property Language $language
 */
class ProductMultilingual extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ProductMultilingual the static model class
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
		return 'tbl_product_multilingual';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('product_id, language_id, name', 'required'),
			array('product_id, language_id, viewed', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>64),
			array('url', 'length', 'max'=>255),
			array('desc', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, product_id, language_id, name, desc, url, viewed', 'safe', 'on'=>'search'),
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
			'product' => array(self::BELONGS_TO, 'Product', 'product_id'),
			'language' => array(self::BELONGS_TO, 'Language', 'language_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'product_id' => 'Product',
			'language_id' => 'Language',
			'name' => 'Name',
			'desc' => 'Desc',
			'url' => 'Url',
			'viewed' => 'Viewed',
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
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('language_id',$this->language_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('desc',$this->desc,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('viewed',$this->viewed);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}