<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- jQeury -->
	<script src="<?php echo Yii::app()->request->baseUrl ?>/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="<?php echo Yii::app()->request->baseUrl ?>/js/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!-- Pure CSS -->
	<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.1.0/pure-min.css">
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->
	
	<!-- icomoon -->
	<!--[if lte IE 7]><script src="<?php echo $this->module->assetsUrl.'/js/' ?>lte-ie7.js"></script><![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
	
	<?php
		$cs = Yii::app()->getClientScript();
		
		// jQuery
		$cs->scriptMap['jquery.js'] = false;
		$cs->scriptMap['jquery.ui.js'] = false;
		$cs->registerCssFile(Yii::app()->request->baseUrl.'/css/pepper-grinder/jquery-ui-1.10.1.custom.min.css');
		
		// Backbone
		$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/underscore-min.js');
		$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/backbone-min.js');
		
		// form validate
		$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.validate.js');
		$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/messages_zh_TW.js');
		
		// icomoon
		$cs->registerCssFile($this->module->assetsUrl.'/css/icomoon.css');
		
		// fine uploader
		$cs->registerScriptFile($this->module->assetsUrl.'/js/jquery.fineuploader-3.5.0.min.js');
		$cs->registerCssFile($this->module->assetsUrl.'/css/fineuploader-3.5.0.css');
		
		// // owned CSS
		$cs->registerCssFile($this->module->assetsUrl.'/css/main.css');
		$cs->registerCssFile($this->module->assetsUrl.'/css/category.css');
		$cs->registerCssFile($this->module->assetsUrl.'/css/product.css');
		$cs->registerCssFile($this->module->assetsUrl.'/css/dropdownMenu.css');
	?>

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
	</div><!-- header -->

	<div id="mainmenu">
		<?php $this->widget('zii.widgets.CMenu',array(
			'activateParents'=>true,
			'items'=>array(
				array('label'=>'Home', 'url'=>array('/site/index')),
				array('label'=>'Product', 'url'=>array('/yiiCommerce/product/admin'), 'items'=>array(
					array('label'=>'Product', 'url'=>array('/yiiCommerce/product/admin')),
					array('label'=>'Category', 'url'=>array('/yiiCommerce/category/index')), 
					array('label'=>'Spec', 'url'=>array('/yiiCommerce/spec/admin')), 
				)), 
				array('label'=>'User', 'url'=>array('/user/admin/admin')), 
				array('label'=>'Language', 'url'=>array('/yiiCommerce/language/admin')), 
				array('label'=>'Login', 'url'=>array('/user/login'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/user/logout'), 'visible'=>!Yii::app()->user->isGuest)
			),
		)); ?>
	</div><!-- mainmenu -->
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

	<div class="clear"></div>

	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> by My Company.<br/>
		All Rights Reserved.<br/>
		<?php echo Yii::powered(); ?>
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>
