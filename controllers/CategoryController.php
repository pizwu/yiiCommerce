<?php

class CategoryController extends Controller
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
					'index', 'expendCategory', 
					'create', 'rename', 'resort', 'delete', 
					'loadProductList', 'resortProductOrder', 
				),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	/**
	 * Show all category list, from root, collapsed
	 */
	public function actionIndex()
	{
		$root = Category::model()->findAll(array(
			'condition'=>'parent_id=:parent_id', 
			'params'=>array(':parent_id'=>0), 
		));
		
		$this->render('index', array(
			'root'=>$root, 
		));
	}

	/**
	 * Expend specified category
	 * given a category_id, return a list of child category, in HTML
	 */
	public function actionExpendCategory()
	{
		$categories = Category::model()->findAll('parent_id=:parent_id', array(':parent_id'=>$_POST['id']));
		
		$list = '';
		foreach ($categories as $key => $category) {
			$list .= $this->listToHtml($category);
		}
		
		echo $list;
	}
	
	/**
	 * Print 1 category list
	 * given: category_id
	 */
	protected function listToHtml($category)
	{
		// check children: have -> collapse / none -> empty
		$childrenCount = Category::model()->count('parent_id=:parent_id', array(':parent_id'=>$category->id));
		if($childrenCount > 0)
			$indicator = '<div class="open-close-indicator collapse">&nbsp;</div>';
		else
			$indicator = '<div class="open-close-indicator">&nbsp;</div>';
		
		$html = <<<EOD
		<li data-id="$category->id">
			$indicator
			<i class="icon-stack"></i>
			<div class="name">$category->name</div>
			<ul></ul>
		</li>
EOD;
		return $html;
	}
	
	/**
	 * Create a category
	 * given: name / parent_id
	 * return: list item
	 */
	public function actionCreate()
	{
		// Yii::log(CVarDumper::DumpAsString($_POST));
		
		// find order
		$count = Category::model()->count('parent_id=:parent_id', array(':parent_id'=>$_POST['parent_id']));
		
		$category = new Category;
		$category->attributes = $_POST;
		$category->sort_order = $count+1;
		$category->date_added = time();
		$category->last_modified = time();
		$category->save();
		
		echo $this->listToHtml($category);
	}
	
	/**
	 * Rename specified category
	 * given: id / name
	 * return: id / name
	 */
	public function actionRename()
	{
		$category = Category::model()->findByPk($_POST['id']);
		$category->name = $_POST['name'];
		$category->save();
		
		echo CJSON::encode(array(
			'id'=>$category->id, 
			'name'=>$category->name, 
		));
	}
	
	/**
	 * Re-Link specified category to other parent
	 * given: idOrder (Array) / parent_id
	 * return: 1
	 */
	public function actionResort()
	{
		// Yii::log(CVarDumper::DumpAsString($_POST));
		
		if(isset($_POST['idOrder'])){
			$categories = Category::model()->findAll('id='.implode(' OR id=', $_POST['idOrder']));
			foreach ($categories as $key => $category) {
				$key = array_search($category->id, $_POST['idOrder']);

				$category->parent_id = $_POST['parent_id'];
				$category->sort_order = $key+1;
				$category->save();
			}
		}
		
		echo CJSON::encode(1);
	}
	
	/**
	 * Delete specified category & it's children
	 * given: id
	 * return 1
	 */
	public function actionDelete()
	{
		$category = Category::model()->findByPk($_POST['id']);
		$category->delete();
		
		echo CJSON::encode(1);
	}
	
	/**
	 * Load product list
	 * need category_id
	 */
	public function actionLoadProductList()
	{
		$productRefs = ProductCategoryRef::model()->with('product.productImageRefs')->findAll(array(
			'condition'=>'t.category_id=:category_id', 
			'params'=>array(':category_id'=>$_POST['category_id']), 
		));
		
		$this->renderPartial('productList', array(
			'productRefs'=>$productRefs, 
		));

	}
	
	/**
	 * Resort product order
	 * Need: category_id / orderArray (product_ids)
	 */
	public function actionResortProductOrder()
	{
		
		// load products in category
		$productsInCategory = ProductCategoryRef::model()->findAll('category_id=:category_id', array(':category_id'=>$_POST['category_id']));
		$orderArray = array_reverse($_POST['orderArray']);
		
		// update order
		foreach ($productsInCategory as $key => $ref) {
			$ref->order = array_search($ref->product_id, $orderArray)+1;
			$ref->save();
		}
		
		echo CJSON::encode(1);
		
	}
}