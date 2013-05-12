<?php

class ImageController extends Controller
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('load'),
				'users'=>array('*'),
			),
			// array('allow', // allow authenticated user to perform 'create' and 'update' actions
			// 	'actions'=>array(''),
			// 	'users'=>array('@'),
			// ),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array(
					'upload',  
				),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	/**
	 * Upload image
	 */
	public function actionUpload()
	{
		Yii::import("application.modules.".Yii::app()->controller->module->id.".extensions.EAjaxUpload.qqFileUploader");
		Yii::import("application.modules.".Yii::app()->controller->module->id.".extensions.WideImage.WideImage");
		
		$jpgCompressLevel = 90;
		$pngCompressLevel = 1;
		$compressedSizeLimit = 1 * 1024 * 1024;
		
		// get image
		$folder = Yii::getPathOfAlias('application.modules.'.Yii::app()->controller->module->id.'.upload');
		$allowedExtensions = array('jpeg', "jpg", 'png');
		$sizeLimit = 10 * 1024 * 1024;// maximum file size in bytes
		$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
		$result = $uploader->handleUpload($folder);
		$type = (strcasecmp($result['extension'], 'jpeg') != 0)? strtolower($result['extension']): 'jpg';
		
		// process image
		$temp = WideImage::load($result['image']);
		// resize
		$temp = $temp->resize(800, 600);
		// compress: jpg / png until file size is small enough
		$compressLevel = ($type=='png')? $pngCompressLevel : $jpgCompressLevel;
		do {
			if($type == 'jpg' && $compressLevel < 10)	break;
			if($type == 'png' && $compressLevel > 9)	break;
			
			$imageString = $temp->asString($type, $compressLevel);
			
			if($type == 'jpg')	$compressLevel -= 10;
			if($type == 'png')	$compressLevel++;
			
		} while(strlen($imageString) > $compressedSizeLimit);
		
		// store to DB
		$image = new Image;
		$image->image = $imageString;
		$image->type = $type;
		$image->size = strlen($imageString);
		$image->save();
		
		// print result
 		echo CJSON::encode(array(
			'success'=>$result['success'], 
			'filename'=>$result['filename'], 
			'id'=>$image->id, 
		));
		
	}

	public function actionLoad()
	{
		Yii::import("application.modules.".Yii::app()->controller->module->id.".extensions.WideImage.WideImage");
		
		$image = Image::model()->findByPk($_REQUEST['id']);
		$temp = WideImage::load($image->image);
		
		if($image->type=='jpg')
			$temp->output('jpg');
		elseif($image->type=='png')
			$temp->output('png');
		
	}
	
	
}