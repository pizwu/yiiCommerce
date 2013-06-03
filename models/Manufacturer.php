<?php

/**
 * This is the model class for table "{{manufacturer}}".
 *
 * The followings are the available columns in table '{{manufacturer}}':
 * @property integer $id
 * @property string $name
 * @property string $manufacturers_image
 * @property integer $date_added
 * @property integer $last_modified
 * @property string $url
 * @property integer $url_clicked
 * @property integer $date_last_click
 *
 * The followings are the available model relations:
 * @property ManufacturerImageRef[] $manufacturerImageRefs
 * @property ManufacturerMultilingual[] $manufacturerMultilinguals
 * @property Product[] $products
 */
class Manufacturer extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Manufacturer the static model class
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
		return '{{manufacturer}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('date_added, last_modified, url_clicked, date_last_click', 'numerical', 'integerOnly'=>true),
			array('name, manufacturers_image', 'length', 'max'=>64),
			array('url', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, manufacturers_image, date_added, last_modified, url, url_clicked, date_last_click', 'safe', 'on'=>'search'),
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
			'manufacturerImageRefs' => array(self::HAS_MANY, 'ManufacturerImageRef', 'manufactorer_id'),
			'manufacturerMultilinguals' => array(self::HAS_MANY, 'ManufacturerMultilingual', 'manufacturer_id'),
			'products' => array(self::HAS_MANY, 'Product', 'manufacturer_id'),
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
			'manufacturers_image' => 'Manufacturers Image',
			'date_added' => 'Date Added',
			'last_modified' => 'Last Modified',
			'url' => 'Url',
			'url_clicked' => 'Url Clicked',
			'date_last_click' => 'Date Last Click',
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
		$criteria->compare('manufacturers_image',$this->manufacturers_image,true);
		$criteria->compare('date_added',$this->date_added);
		$criteria->compare('last_modified',$this->last_modified);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('url_clicked',$this->url_clicked);
		$criteria->compare('date_last_click',$this->date_last_click);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}