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
					'checkout', 'addressBook', 
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
	
	/**
	 * Checkout
	 */
	public function actionCheckout()
	{
		// load checkout form
		if(empty($_POST)){
			
			// load 1st address
			$addressBooks = AddressBook::model()->findAll('user_id=:user_id', array(':user_id'=>Yii::app()->user->id));
			if(empty($addressBooks))
				$addressBooks = array(new AddressBook);

			// load shopping cart
			$shoppingCart = ShoppingCart::model()->with(array(
				'product.productImageRefs', 
				'user',
			))->findAll('t.user_id=:user_id', array(':user_id'=>Yii::app()->user->id));

			$this->render('checkout', array(
				'addressBooks'=>$addressBooks, 
				'shoppingCart'=>$shoppingCart, 
			));
			
		}
		// checkout process
		else {
			
			// Yii::log(CVarDumper::DumpAsString($_POST));
			
			// if shopping cart is empty, cancel the order
			$shoppingCart = ShoppingCart::model()->with(array(
				'product', 
			))->findAll('t.user_id=:user_id', array(':user_id'=>Yii::app()->user->id));
			if(empty($shoppingCart)){
				$this->render('checkoutSuccess', array(
				));
				Yii::app()->end();
			}
			
			// create order
			$order = new Order;
			$order->user_id = Yii::app()->user->id;
			$order->payment_method = 'none';
			$order->last_modified = time();
			$order->date_purchased = time();
			$order->status_id = 1;
			$order->save();
			
			
			// address book
			// check existing address book / new input, 如果資料有變就加入新的address book
			if(isset($_POST['shipping']['address_book_id']) && !empty($_POST['shipping']['address_book_id']))
				$addressBook = AddressBook::model()->findByPk($_POST['shipping']['address_book_id']);
			else
				$addressBook = new AddressBook;
			
			// compare 2 array
			$addressBookIterator = $addressBook->getIterator();
			do{
				$key = $addressBookIterator->key();
				
				if(!isset($_POST['shipping'][$key]) || $_POST['shipping'][$key] == $addressBook[$key]){
					$addressBookIterator->next();
					continue;
				}
				else
					break;
				
			}while($addressBookIterator->key());

			// 2 data are different, because it break from iteration
			// create an new address book
			if($addressBookIterator->key()){
				$addressBook = new AddressBook;
				$addressBook->attributes = $_POST['shipping'];
				$addressBook->user_id = Yii::app()->user->id;
				$addressBook->save();
			}
			
			// shipping info
			$shippingInfo = new OrderShippingInfo;
			$shippingInfo->attributes = $_POST['shipping'];
			$shippingInfo->order_id = $order->id;
			$shippingInfo->save();
			
			// // billing info
			// $billingInfo = new OrderBillingInfo;
			// $billingInfo->attributes = $_POST['billing'];
			// $billingInfo->order_id = $order->id;
			// $billingInfo->save();
			
			// credit card info
			
			// order - product reference
			$subtotal = 0;
			foreach ($shoppingCart as $key => $cart) {
				
				// create reference to product
				$orderProductRef = new OrderProductRef;
				$orderProductRef->order_id = $order->id;
				$orderProductRef->product_id = $cart->product_id;
				$orderProductRef->product_name = $cart->product->name;
				$orderProductRef->product_price = $cart->product->price;
				$orderProductRef->quantity = $cart->quantity;
				$orderProductRef->final_price = $cart->final_price;
				$orderProductRef->product_tax = 0;
				$orderProductRef->save();
				
				$subtotal += $orderProductRef->final_price;
				
				// remove shopping cart immediately
				$cart->delete();
			}
			
			// order total
			$total = new OrderTotal;
			$total->order_id = $order->id;
			$total->subtotal = $subtotal;
			$total->shipping = 0;
			$total->total = $total->subtotal + $total->shipping;
			$total->save();
			
			// order status history
			$orderStatusHistory = new OrderStatusHistory;
			$orderStatusHistory->order_id = $order->id;
			$orderStatusHistory->order_status_id = $order->status_id;
			$orderStatusHistory->date_added = time();
			$orderStatusHistory->notified = 0;
			$orderStatusHistory->comment = $_POST['comment'];
			$orderStatusHistory->save();
			
			// send notification email (not done yet)
			
			$orderStatusHistory->notified = 1;
			$orderStatusHistory->save();
			
			// all done, print success page
			$this->render('checkoutSuccess');
		}
		
	}
	
	/**
	 * Address Book
	 * Need: id (address book id)
	 * return: Address book model
	 */
	public function actionAddressBook()
	{
		$addressBook = AddressBook::model()->findByPk($_POST['id']);
		
		echo CJSON::encode($addressBook);
	}
	
}