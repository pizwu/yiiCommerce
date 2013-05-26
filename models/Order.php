<?php

/**
 * This is the model class for table "{{order}}".
 *
 * The followings are the available columns in table '{{order}}':
 * @property integer $id
 * @property integer $customer_id
 * @property string $customer_name
 * @property string $customer_company
 * @property string $customer_street_address
 * @property string $customer_suburb
 * @property string $customer_city
 * @property string $customer_postcode
 * @property string $customer_state
 * @property string $customer_country
 * @property string $customer_phone
 * @property string $customer_email
 * @property string $delivery_name
 * @property string $delivery_company
 * @property string $delivery_street_address
 * @property string $delivery_suburb
 * @property string $delivery_city
 * @property string $delivery_postcode
 * @property string $delivery_state
 * @property string $delivery_country
 * @property string $billing_name
 * @property string $billing_company
 * @property string $billing_street_address
 * @property string $billing_suburb
 * @property string $billing_city
 * @property string $billing_postcode
 * @property string $billing_state
 * @property string $billing_country
 * @property string $payment_method
 * @property string $cc_type
 * @property string $cc_owner
 * @property string $cc_number
 * @property string $cc_expire
 * @property integer $last_modified
 * @property integer $date_purchased
 * @property integer $order_status
 * @property integer $order_date_finished
 * @property string $currency
 * @property string $currency_value
 *
 * The followings are the available model relations:
 * @property OrderStatus $orderStatus
 * @property OrderProductRef[] $orderProductRefs
 * @property OrderStatusHistory[] $orderStatusHistories
 * @property OrderTotal[] $orderTotals
 */
class Order extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Order the static model class
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
		return '{{order}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id, customer_name, customer_street_address, customer_city, customer_postcode, customer_country, customer_phone, customer_email, delivery_name, delivery_street_address, delivery_city, delivery_postcode, delivery_country, billing_name, billing_street_address, billing_city, billing_postcode, billing_country, payment_method, last_modified, order_status', 'required'),
			array('customer_id, last_modified, date_purchased, order_status, order_date_finished', 'numerical', 'integerOnly'=>true),
			array('customer_name, customer_company, customer_street_address, customer_suburb, customer_city, customer_postcode, customer_state, customer_country, customer_phone, customer_email, delivery_name, delivery_company, delivery_street_address, delivery_suburb, delivery_city, delivery_postcode, delivery_state, delivery_country, billing_name, billing_company, billing_street_address, billing_suburb, billing_city, billing_postcode, billing_state, billing_country, payment_method, cc_owner', 'length', 'max'=>255),
			array('cc_type', 'length', 'max'=>20),
			array('cc_number', 'length', 'max'=>32),
			array('cc_expire', 'length', 'max'=>4),
			array('currency', 'length', 'max'=>3),
			array('currency_value', 'length', 'max'=>14),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, customer_id, customer_name, customer_company, customer_street_address, customer_suburb, customer_city, customer_postcode, customer_state, customer_country, customer_phone, customer_email, delivery_name, delivery_company, delivery_street_address, delivery_suburb, delivery_city, delivery_postcode, delivery_state, delivery_country, billing_name, billing_company, billing_street_address, billing_suburb, billing_city, billing_postcode, billing_state, billing_country, payment_method, cc_type, cc_owner, cc_number, cc_expire, last_modified, date_purchased, order_status, order_date_finished, currency, currency_value', 'safe', 'on'=>'search'),
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
			'orderStatus' => array(self::BELONGS_TO, 'OrderStatus', 'order_status'),
			'orderProductRefs' => array(self::HAS_MANY, 'OrderProductRef', 'order_id'),
			'orderStatusHistories' => array(self::HAS_MANY, 'OrderStatusHistory', 'order_id'),
			'orderTotals' => array(self::HAS_MANY, 'OrderTotal', 'order_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'customer_id' => 'Customer',
			'customer_name' => 'Customer Name',
			'customer_company' => 'Customer Company',
			'customer_street_address' => 'Customer Street Address',
			'customer_suburb' => 'Customer Suburb',
			'customer_city' => 'Customer City',
			'customer_postcode' => 'Customer Postcode',
			'customer_state' => 'Customer State',
			'customer_country' => 'Customer Country',
			'customer_phone' => 'Customer Phone',
			'customer_email' => 'Customer Email',
			'delivery_name' => 'Delivery Name',
			'delivery_company' => 'Delivery Company',
			'delivery_street_address' => 'Delivery Street Address',
			'delivery_suburb' => 'Delivery Suburb',
			'delivery_city' => 'Delivery City',
			'delivery_postcode' => 'Delivery Postcode',
			'delivery_state' => 'Delivery State',
			'delivery_country' => 'Delivery Country',
			'billing_name' => 'Billing Name',
			'billing_company' => 'Billing Company',
			'billing_street_address' => 'Billing Street Address',
			'billing_suburb' => 'Billing Suburb',
			'billing_city' => 'Billing City',
			'billing_postcode' => 'Billing Postcode',
			'billing_state' => 'Billing State',
			'billing_country' => 'Billing Country',
			'payment_method' => 'Payment Method',
			'cc_type' => 'Cc Type',
			'cc_owner' => 'Cc Owner',
			'cc_number' => 'Cc Number',
			'cc_expire' => 'Cc Expire',
			'last_modified' => 'Last Modified',
			'date_purchased' => 'Date Purchased',
			'order_status' => 'Order Status',
			'order_date_finished' => 'Order Date Finished',
			'currency' => 'Currency',
			'currency_value' => 'Currency Value',
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
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('customer_name',$this->customer_name,true);
		$criteria->compare('customer_company',$this->customer_company,true);
		$criteria->compare('customer_street_address',$this->customer_street_address,true);
		$criteria->compare('customer_suburb',$this->customer_suburb,true);
		$criteria->compare('customer_city',$this->customer_city,true);
		$criteria->compare('customer_postcode',$this->customer_postcode,true);
		$criteria->compare('customer_state',$this->customer_state,true);
		$criteria->compare('customer_country',$this->customer_country,true);
		$criteria->compare('customer_phone',$this->customer_phone,true);
		$criteria->compare('customer_email',$this->customer_email,true);
		$criteria->compare('delivery_name',$this->delivery_name,true);
		$criteria->compare('delivery_company',$this->delivery_company,true);
		$criteria->compare('delivery_street_address',$this->delivery_street_address,true);
		$criteria->compare('delivery_suburb',$this->delivery_suburb,true);
		$criteria->compare('delivery_city',$this->delivery_city,true);
		$criteria->compare('delivery_postcode',$this->delivery_postcode,true);
		$criteria->compare('delivery_state',$this->delivery_state,true);
		$criteria->compare('delivery_country',$this->delivery_country,true);
		$criteria->compare('billing_name',$this->billing_name,true);
		$criteria->compare('billing_company',$this->billing_company,true);
		$criteria->compare('billing_street_address',$this->billing_street_address,true);
		$criteria->compare('billing_suburb',$this->billing_suburb,true);
		$criteria->compare('billing_city',$this->billing_city,true);
		$criteria->compare('billing_postcode',$this->billing_postcode,true);
		$criteria->compare('billing_state',$this->billing_state,true);
		$criteria->compare('billing_country',$this->billing_country,true);
		$criteria->compare('payment_method',$this->payment_method,true);
		$criteria->compare('cc_type',$this->cc_type,true);
		$criteria->compare('cc_owner',$this->cc_owner,true);
		$criteria->compare('cc_number',$this->cc_number,true);
		$criteria->compare('cc_expire',$this->cc_expire,true);
		$criteria->compare('last_modified',$this->last_modified);
		$criteria->compare('date_purchased',$this->date_purchased);
		$criteria->compare('order_status',$this->order_status);
		$criteria->compare('order_date_finished',$this->order_date_finished);
		$criteria->compare('currency',$this->currency,true);
		$criteria->compare('currency_value',$this->currency_value,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}