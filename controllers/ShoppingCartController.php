<?php

class ShoppingCartController extends Controller
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
					'add', 
					'admin', 
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
	 * Add product to shopping cart
	 * Need: id (product_id)
	 */
	public function actionAdd()
	{
		
		$cart = ShoppingCart::model()->find('user_id=:user_id AND product_id=:product_id', array(
			':user_id'=>Yii::app()->user->id, 
			':product_id'=>$_REQUEST['id'], 
		));
		
		$product = Product::model()->findByPk($_REQUEST['id']);
		
		if(empty($cart)){
			$cart = new ShoppingCart;
			$cart->user_id = Yii::app()->user->id;
			$cart->product_id = $_REQUEST['id'];
			$cart->quantity = $_REQUEST['quantity'];
			$cart->final_price = $product->price * $cart->quantity;
		}
		else{
			$cart->quantity += $_REQUEST['quantity'];
			$cart->final_price = $product->price * $cart->quantity;
		}
		
		$cart->timestamp = time();
		$cart->save();
		
		echo CJSON::encode(1);

	}
	
	/**
	 * Shopping cart panel
	 * load & list shopping cart
	 */
	public function actionAdmin()
	{
		
		$shoppingCart = ShoppingCart::model()->with(array(
			'product.productImageRefs', 
			'user',
		))->findAll('t.user_id=:user_id', array(':user_id'=>Yii::app()->user->id));
		
		$this->render('admin', array(
			'shoppingCart'=>$shoppingCart, 
		));
		
	}

}