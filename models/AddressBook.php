<?php

/**
 * This is the model class for table "{{address_book}}".
 *
 * The followings are the available columns in table '{{address_book}}':
 * @property integer $id
 * @property integer $user_id
 * @property string $company
 * @property string $first_name
 * @property string $last_name
 * @property string $phone
 * @property string $street_address
 * @property string $suburb
 * @property string $postcode
 * @property string $city
 * @property string $state
 * @property string $country
 *
 * The followings are the available model relations:
 * @property Users $user
 */
class AddressBook extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AddressBook the static model class
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
		return '{{address_book}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, first_name, last_name, phone, street_address, postcode, city, country', 'required'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('company, first_name, last_name, suburb, postcode, city, state, country', 'length', 'max'=>255),
			array('phone', 'length', 'max'=>45),
			array('street_address', 'length', 'max'=>512),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, company, first_name, last_name, phone, street_address, suburb, postcode, city, state, country', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'company' => 'Company',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'phone' => 'Phone',
			'street_address' => 'Street Address',
			'suburb' => 'Suburb',
			'postcode' => 'Postcode',
			'city' => 'City',
			'state' => 'State',
			'country' => 'Country',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('company',$this->company,true);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('street_address',$this->street_address,true);
		$criteria->compare('suburb',$this->suburb,true);
		$criteria->compare('postcode',$this->postcode,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('country',$this->country,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}