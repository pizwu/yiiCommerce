<?php

class ProductController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='/layouts/column2';
	
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
			// 	'actions'=>array(''),
			// 	'users'=>array('*'),
			// ),
			// array('allow', // allow authenticated user to perform 'create' and 'update' actions
			// 	'actions'=>array(''),
			// 	'users'=>array('@'),
			// ),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array(
					'admin', 'printCreateForm', 'printEditForm', 'printCategorySelector', 
					'save', 'unlinkFromCategory', 'delete', 
				),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	/**
	 * Show all products, and filter
	 */
	public function actionAdmin()
	{
		$products = Product::model()->findAll(array('limit'=>20));
		
		$this->render('admin', array(
			'products'=>$products, 
		));
	}

	/**
	 * Print create form
	 */
	public function actionPrintCreateForm()
	{
		$product = new Product;
		
		$this->renderPartial('form', array(
			'product'=>$product, 
		));
	}
	
	/**
	 * Print edit form
	 */
	public function actionPrintEditForm()
	{
		$product = Product::model()->findByPk($_POST['id']);
		
		$this->renderPartial('form', array(
			'product'=>$product, 
		));
	}
	
	/**
	 * Print category selector
	 */
	public function actionPrintCategorySelector()
	{
		echo '<div id="category-selector-list">';
			echo "<ul>";
			$this->printCategorySelector(0, $_POST['selectedCategoryIdArray']);
			echo "</ul>";
		
			// buttons
			echo '<hr />';
		
			echo '<button type="button" id="select-category">select</button>';
			echo '<button type="button" id="cancel-select-category"><i class="icon-close"></i></button>';
		echo '</div>';
	}
	
	/**
	 * Recursive function for actionPrintCategorySelector
	 */
	protected function printCategorySelector($parent_id, $selectedIds)
	{
		$categories = Category::model()->findAll('parent_id=:parent_id', array(':parent_id'=>$parent_id));
		foreach ($categories as $key => $category) {
			$selectedClass = (in_array($category->id, $selectedIds))? 'selected': '';
			echo "<li data-id='$category->id' class='$selectedClass'>";
				echo "<i class='icon-stack'></i><span class='name'>$category->name</span>";
				echo "<ul>";
				
				$this->printCategorySelector($category->id, $selectedIds);
				
				echo "</ul>";
			echo "</li>";
		}
	}
	
	/**
	 * Save product
	 * Save: create / update
	 */
	public function actionSave()
	{
		// Yii::log(CVarDumper::DumpAsString($_POST));
		
		if(empty($_POST['id']))
			$product = new Product;
		else
			$product = Product::model()->findByPk($_POST['id']);
			
		$product->attributes = $_POST;
		$product->status = ($product->status=='on')? 1: 0;
		$product->date_added = time();
		$product->save();
		
		// category reference
		// remove old
		$categoryRefs = ProductCategoryRef::model()->findAll('product_id=:product_id', array(':product_id'=>$product->id));
		foreach ($categoryRefs as $key => $ref) {
			$ref->delete();
		}
		// create new
		if(isset($_POST['category'])){
			foreach ($_POST['category'] as $key => $id) {
				$ref = new ProductCategoryRef;
				$ref->product_id = $product->id;
				$ref->category_id = $id;
				$ref->save();
			}
		}
		
		// image reference
		// remove old
		$imageRefs = ProductImageRef::model()->findAll('product_id=:product_id', array(':product_id'=>$product->id));
		foreach ($imageRefs as $key => $ref) {
			$ref->delete();
		}
		if(isset($_POST['image'])){
			foreach ($_POST['image'] as $key => $id) {
				$ref = new ProductImageRef;
				$ref->product_id = $product->id;
				$ref->image_id = $id;
				$ref->sort_order = $key+1;
				$ref->save();
			}
		}
		
		echo CJSON::encode(1);
		
	}
	
	/**
	 * Unlink category
	 * given: category_id, product_id
	 */
	public function actionUnlinkFromCategory()
	{
		$ref = ProductCategoryRef::model()->find('category_id=:category_id AND product_id=:product_id', array(
			':category_id'=>$_POST['category_id'], 
			':product_id'=>$_POST['product_id'], 
		));
		$ref->delete();
		
		echo CJSON::encode(1);
	}
	
	/**
	 * Delete product
	 * given: product_id
	 */
	public function actionDelete()
	{
		$product = Product::model()->findByPk($_POST['id']);
		$product->delete();
		
		echo CJSON::encode(1);
	}
}