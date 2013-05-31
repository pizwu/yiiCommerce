<?php
/* @var $this SpecController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Specs',
);

$this->menu=array(
	array('label'=>'Create Spec', 'url'=>array('create')),
	array('label'=>'Manage Spec', 'url'=>array('admin')),
);
?>

<h1>Specs</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
