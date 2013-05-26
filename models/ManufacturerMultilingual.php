<?php

/**
 * This is the model class for table "tbl_manufacturer_multilingual".
 *
 * The followings are the available columns in table 'tbl_manufacturer_multilingual':
 * @property integer $id
 * @property integer $manufacturer_id
 * @property integer $language_id
 * @property string $name
 * @property string $manufacturers_url
 * @property integer $url_clicked
 * @property string $date_last_click
 *
 * The followings are the available model relations:
 * @property Manufacturer $manufacturer
 * @property Language $language
 */
class ManufacturerMultilingual extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ManufacturerMultilingual the static model class
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
		return 'tbl_manufacturer_multilingual';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('manufacturer_id, language_id, name', 'required'),
			array('manufacturer_id, language_id, url_clicked', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>64),
			array('manufacturers_url', 'length', 'max'=>255),
			array('date_last_click', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, manufacturer_id, language_id, name, manufacturers_url, url_clicked, date_last_click', 'safe', 'on'=>'search'),
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
			'manufacturer_id' => 'Manufacturer',
			'language_id' => 'Language',
			'name' => 'Name',
			'manufacturers_url' => 'Manufacturers Url',
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
		$criteria->compare('manufacturer_id',$this->manufacturer_id);
		$criteria->compare('language_id',$this->language_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('manufacturers_url',$this->manufacturers_url,true);
		$criteria->compare('url_clicked',$this->url_clicked);
		$criteria->compare('date_last_click',$this->date_last_click,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}