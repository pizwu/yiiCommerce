<?php

/**
 * This is the model class for table "{{order}}".
 *
 * The followings are the available columns in table '{{order}}':
 * @property integer $id
 * @property integer $user_id
 * @property string $payment_method
 * @property string $cc_type
 * @property string $cc_owner
 * @property string $cc_number
 * @property string $cc_expire
 * @property integer $last_modified
 * @property integer $date_purchased
 * @property integer $status_id
 * @property integer $date_finished
 * @property string $currency
 * @property string $currency_value
 *
 * The followings are the available model relations:
 * @property Users $user
 * @property OrderStatus $status
 * @property OrderBillingInfo[] $orderBillingInfos
 * @property OrderProductRef[] $orderProductRefs
 * @property OrderShippingInfo[] $orderShippingInfos
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
			array('user_id, payment_method, last_modified, status_id', 'required'),
			array('user_id, last_modified, date_purchased, status_id, date_finished', 'numerical', 'integerOnly'=>true),
			array('payment_method, cc_owner', 'length', 'max'=>255),
			array('cc_type', 'length', 'max'=>20),
			array('cc_number', 'length', 'max'=>32),
			array('cc_expire', 'length', 'max'=>4),
			array('currency', 'length', 'max'=>3),
			array('currency_value', 'length', 'max'=>14),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, payment_method, cc_type, cc_owner, cc_number, cc_expire, last_modified, date_purchased, status_id, date_finished, currency, currency_value', 'safe', 'on'=>'search'),
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
			'status' => array(self::BELONGS_TO, 'OrderStatus', 'status_id'),
			'orderBillingInfos' => array(self::HAS_MANY, 'OrderBillingInfo', 'order_id'),
			'orderProductRefs' => array(self::HAS_MANY, 'OrderProductRef', 'order_id'),
			'orderShippingInfos' => array(self::HAS_MANY, 'OrderShippingInfo', 'order_id'),
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
			'user_id' => 'User',
			'payment_method' => 'Payment Method',
			'cc_type' => 'Cc Type',
			'cc_owner' => 'Cc Owner',
			'cc_number' => 'Cc Number',
			'cc_expire' => 'Cc Expire',
			'last_modified' => 'Last Modified',
			'date_purchased' => 'Date Purchased',
			'status_id' => 'Status',
			'date_finished' => 'Date Finished',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('payment_method',$this->payment_method,true);
		$criteria->compare('cc_type',$this->cc_type,true);
		$criteria->compare('cc_owner',$this->cc_owner,true);
		$criteria->compare('cc_number',$this->cc_number,true);
		$criteria->compare('cc_expire',$this->cc_expire,true);
		$criteria->compare('last_modified',$this->last_modified);
		$criteria->compare('date_purchased',$this->date_purchased);
		$criteria->compare('status_id',$this->status_id);
		$criteria->compare('date_finished',$this->date_finished);
		$criteria->compare('currency',$this->currency,true);
		$criteria->compare('currency_value',$this->currency_value,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}