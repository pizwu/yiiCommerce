<?php

class ShopController extends Controller
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array(
					'productListInCategory', 
				),
				'users'=>array('*'),
			),
			// array('allow', // allow authenticated user to perform 'create' and 'update' actions
			// 	'actions'=>array(''),
			// 	'users'=>array('@'),
			// ),
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
	 * Product list in category
	 * Need: id (category_id)
	 */
	public function actionProductListInCategory()
	{
		$category_id = (isset($_REQUEST['id']))? $_REQUEST['id'] : 0;
		
		// get this category info
		$category = Category::model()->findByPk($category_id);
		
		// ancestor category
		$breadcrumbs = array();
		if(!empty($category)){
			
			$breadcrumbs = array(
				$category->name 
			);
			$parentCategoryId = $category->parent_id;
			
			while ( $parentCategory = Category::model()->findByPk($parentCategoryId) ) {
				
				if(empty($parentCategory))	break;
				$breadcrumbs[$parentCategory->name] = array("shop/productListInCategory", 'id'=>$parentCategory->id);
				$parentCategoryId = $parentCategory->parent_id;

			}

			$breadcrumbs = array_reverse($breadcrumbs);
		}
		
		// find child categories
		$childCategories = Category::model()->findAll('parent_id=:parent_id', array(':parent_id'=>$category_id));
		
		// find products in category
		$products = Product::model()->with(array(
			'productCategoryRefs'=>array(
				'condition'=>'category_id=:category_id', 
				'params'=>array(':category_id'=>$category_id), 
			), 
			'productImageRefs', 
		))->findAll();
		
		$this->render('productListInCategory', array(
			'breadcrumbs'=>$breadcrumbs, 
			'category'=>$category, 
			'childCategories'=>$childCategories, 
			'products'=>$products, 
		));
		
	}

}