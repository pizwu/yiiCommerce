<?php

/**
 * This is the model class for table "osc_language".
 *
 * The followings are the available columns in table 'osc_language':
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property integer $sort_order
 *
 * The followings are the available model relations:
 * @property CategoryMultilingual[] $categoryMultilinguals
 * @property LanguageImageRef[] $languageImageRefs
 * @property ManufacturerMultilingual[] $manufacturerMultilinguals
 * @property ProductAttributeMultilingual[] $productAttributeMultilinguals
 * @property ProductMultilingual[] $productMultilinguals
 */
class Language extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Language the static model class
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
		return 'osc_language';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, code', 'required'),
			array('sort_order', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>32),
			array('code', 'length', 'max'=>4),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, code, sort_order', 'safe', 'on'=>'search'),
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
			'categoryMultilinguals' => array(self::HAS_MANY, 'CategoryMultilingual', 'language_id'),
			'languageImageRefs' => array(self::HAS_MANY, 'LanguageImageRef', 'language_id'),
			'manufacturerMultilinguals' => array(self::HAS_MANY, 'ManufacturerMultilingual', 'language_id'),
			'productAttributeMultilinguals' => array(self::HAS_MANY, 'ProductAttributeMultilingual', 'language_id'),
			'productMultilinguals' => array(self::HAS_MANY, 'ProductMultilingual', 'language_id'),
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
			'code' => 'Code',
			'sort_order' => 'Sort Order',
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
		$criteria->compare('code',$this->code,true);
		$criteria->compare('sort_order',$this->sort_order);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}