<?php

/**
 * This is the model class for table "{{order_status_history}}".
 *
 * The followings are the available columns in table '{{order_status_history}}':
 * @property integer $id
 * @property integer $order_id
 * @property integer $order_status_id
 * @property integer $date_added
 * @property integer $notified
 * @property string $comment
 *
 * The followings are the available model relations:
 * @property Order $order
 * @property OrderStatus $orderStatus
 */
class OrderStatusHistory extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return OrderStatusHistory the static model class
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
		return '{{order_status_history}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('order_id, order_status_id, date_added', 'required'),
			array('order_id, order_status_id, date_added, notified', 'numerical', 'integerOnly'=>true),
			array('comment', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, order_id, order_status_id, date_added, notified, comment', 'safe', 'on'=>'search'),
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
			'orderStatus' => array(self::BELONGS_TO, 'OrderStatus', 'order_status_id'),
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
			'order_status_id' => 'Order Status',
			'date_added' => 'Date Added',
			'notified' => 'Notified',
			'comment' => 'Comment',
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
		$criteria->compare('order_status_id',$this->order_status_id);
		$criteria->compare('date_added',$this->date_added);
		$criteria->compare('notified',$this->notified);
		$criteria->compare('comment',$this->comment,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}