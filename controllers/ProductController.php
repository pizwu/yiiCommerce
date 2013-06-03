<?php

class ProductController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='/layouts/column1';
	
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
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
					'admin', 'printCategorySelector', 
					'save', 'unlinkFromCategory', 'delete', 
					// form elements
					'form', 'imagePackHTML', 
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
		// $products = Product::model()->findAll(array('limit'=>20));
		// 
		// $this->render('admin', array(
		// 	'products'=>$products, 
		// ));
		
		$model=new Product('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Product']))
			$model->attributes=$_GET['Product'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Print category selector
	 */
	public function actionPrintCategorySelector()
	{
		$selectedCategoryIdArray = (isset($_POST['selectedCategoryIdArray']))? $_POST['selectedCategoryIdArray'] : array();
		
		echo '<div id="category-selector-list">';
			echo "<ul>";
			$this->printCategorySelector(0, $selectedCategoryIdArray);
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
			$product = Product::model()->with(array(
				'productCategoryRefs', 
				'productImageRefs', 
				'productTagRefs', 
				'productSpecRefs', 
				'productSeos', 
			))->findByPk($_POST['id']);
			
		$product->attributes = $_POST;
		$product->status = ($product->status=='on')? 1: 0;
		$product->price = (empty($_POST['price']))? 0 : $_POST['price'];
		$product->date_added = time();
		$product->save();
		
		// category reference
		// load old, check which should stay, which should delete, by $_POST['category']
		$originalCategoryRefs = ProductCategoryRef::model()->findAll('product_id=:product_id', array(':product_id'=>$product->id));
		foreach ($originalCategoryRefs as $key => $ref) {
			
			if(isset($_POST['category_id'])){
				
				$index = array_search($ref->category_id, $_POST['category']);
				
				// keep original reference, do nothing
				if($index)			
					unset($_POST['category'][$index]);
				// remove this reference
				else 				
					$ref->delete();
				
			}
			else
				$ref->delete();
			
		}
		// create new from the rest of $_POST['category']
		foreach ($_POST['category'] as $key => $id) {
			$ref = new ProductCategoryRef;
			$ref->product_id = $product->id;
			$ref->category_id = $id;
			
			// find the max order in this category
			// $criteria = new CDbCriteria;
			// $criteria->select = 'MAX(t.order) as max_order';
			// $criteria->condition = 't.category_id=:category_id';
			// $criteria->params = array(':category_id'=>$id);
			// $maxOrder = ProductCategoryRef::model()->find($criteria);
			$maxOrder = ProductCategoryRef::model()->find(array(
				'select'=>'MAX(t.order) as max_order', 
				'condition'=>'t.category_id=:category_id', 
				'params'=>array(':category_id'=>$id), 
			));
			$ref->order = (isset($maxOrder->max_order))? $maxOrder->max_order+1 : 1;
			
			$ref->save();
		}
		
		// image reference
		// remove old
		$imageRefs = $product->productImageRefs;
		foreach ($imageRefs as $key => $ref) {
			$ref->delete();
		}
		if(isset($_POST['image'])){
			foreach ($_POST['image'] as $key => $id) {
				$ref = new ProductImageRef;
				$ref->product_id = $product->id;
				$ref->image_id = $id;
				$ref->sort_order = $key+1;
				$ref->main = ($id == $_POST['main_image_id'])? 1: 0;
				$ref->save();
			}
		}
		
		// tags reference
		// remove old
		$tagRefs = $product->productTagRefs;
		foreach ($tagRefs as $key => $ref) {
			$ref->delete();
		}
		// create new reference
		$tags = (isset($_POST['tags']))? explode(',', $_POST['tags']) : array() ;
		foreach ($tags as $key => $tagName) {
			$tagName = trim($tagName);
			
			// find tag
			$tag = Tag::model()->find('name=:name', array(':name'=>$tagName));
			if(empty($tag)){
				$tag = new Tag;
				$tag->name = $tagName;
				$tag->save();
			}
			
			// make reference
			$ref = new ProductTagRef;
			$ref->product_id = $product->id;
			$ref->tag_id = $tag->id;
			$ref->save();
		}
		
		// spec reference
		$specList = Spec::model()->findAll(array('order'=>'t.order asc'));
		// remove old
		$specRefs = $product->productSpecRefs;
		foreach ($specRefs as $key => $ref) {
			$ref->delete();
		}
		// create new reference
		$specValue = (isset($_POST['spec']))? $_POST['spec'] : array();
		foreach ($specValue as $key => $value) {
			
			if(is_null($value))	continue;
			
			$ref = new ProductSpecRef;
			$ref->product_id = $product->id;
			$ref->spec_id = $specList[$key]->id;
			$ref->value = $value;
			$ref->save();
		}
		
		// SEO
		if(!empty($product->productSeos))
			$seo = $product->productSeos[0];
		else {
			$seo = new ProductSeo;
			$seo->product_id = $product->id;
		}
		$seo->title = $_POST['seo_title'];
		$seo->description = $_POST['seo_description'];
		$seo->keywords = $_POST['seo_keywords'];
		$seo->save();
		
		
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
	
	/**
	 * Print create/edit form
	 */
	public function actionForm()
	{
		
		// product
		if(!isset($_POST['id']) || empty($_POST['id']))
			$product = new Product;
		else{
			$product = Product::model()->with(array(
				'productTagRefs.tag', 
				'productSeos', 
				'productImageRefs', 
				'productCategoryRefs', 
			))->findByPk($_POST['id']);
		}
			
		// prepare spec list & value
		$specList = Spec::model()->findAll(array(
			'order'=>'t.order asc', 
		));
		
		$specValue = array();
		foreach ($specList as $key => $spec) {
			
			$ref = ProductSpecRef::model()->find('spec_id=:spec_id AND product_id=:product_id', array(
				':spec_id'=>$spec->id, 
				':product_id'=>$product->id, 
			));
			
			$specValue[] = (empty($ref))? null: $ref->value;
			
		}
		
		// main image
		$mainImageRef = ProductImageRef::model()->with('image')->find('product_id=:product_id AND main=:main', array(
			':product_id'=>$product->id, 
			':main'=>1, 
		));
		$mainImage = (!empty($mainImageRef))? $mainImageRef->image : null;
		
		
		$this->renderPartial('form', array(
			'product'=>$product, 
			'specList'=>$specList, 
			'specValue'=>$specValue, 
			'mainImage'=>$mainImage, 
		));
		
	}
	
	/**
	 * Load image pack HTML
	 * Need: id (image id)
	 */
	public function actionImagePackHTML($id=null)
	{
		
		$id = (isset($id))? $id : $_POST['id'];
		$image = Image::model()->with('productImageRefs')->findByPk($id);
		$link = CHtml::normalizeUrl(array("image/load", 'id'=>$image->id));
		
		// main image class / tag
		$mainClass = (!empty($image->productImageRefs) && $image->productImageRefs[0]->main)? 'main' : null;
		$mainTag = (!empty($image->productImageRefs) && $image->productImageRefs[0]->main)? '<div class="main-image-tag">main</div>': null;
		
		$imagePack = <<<EOD
		<div class="image-pack $mainClass">
			$mainTag
			<img src="$link" width="140" alt="product image" />
			<input type="hidden" class="image-id" name="image[]" value="$image->id" />
			<div class="remove-image"><i class="icon-close"></i></div>
		</div>
EOD;
		
		echo $imagePack;
		
	}
	
	// tools
	// ===============================================
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Product::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='product-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
}