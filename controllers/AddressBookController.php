<?php

class AddressBookController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='/layouts/frontSideColumn1';
	
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}
	
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			// array('allow',  // allow all users to perform 'index' and 'view' actions
			// 	'actions'=>array(
			// 		'', 
			// 	),
			// 	'users'=>array('*'),
			// ),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array(
					'read', 
				),
				'users'=>array('@'),
			),
			// array('allow', // allow admin user to perform 'admin' and 'delete' actions
			// 	'actions'=>array(''),
			// 	'users'=>array('admin'),
			// ),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	/**
	 * Read Address Book
	 * Need: id (address book id)
	 * return: Address book model
	 */
	public function actionRead()
	{
		$addressBook = AddressBook::model()->findByPk($_POST['id']);
		
		echo CJSON::encode($addressBook);
	}
	
}