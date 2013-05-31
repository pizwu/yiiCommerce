<?php
/* @var $this SpecController */
/* @var $model Spec */

$this->breadcrumbs=array(
	'Specs'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Spec', 'url'=>array('index')),
	array('label'=>'Manage Spec', 'url'=>array('admin')),
);
?>

<h1>Create Spec</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>