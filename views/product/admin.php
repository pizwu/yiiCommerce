<?php
/* @var $this ProductController */
/* @var $model Product */

// $this->breadcrumbs=array(
// 	'Products'=>array('index'),
// 	'Manage',
// );

// $this->menu=array(
// 	array('label'=>'List Product', 'url'=>array('index')),
// 	array('label'=>'Create Product', 'url'=>array('create')),
// );

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('product-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<!-- Product form dialog -->
<?php 
	$this->widget('ProductFormDialogWidget', array(
		'basePath'=>$this->module->assetsUrl, 
	));
?>

<h1>Manage Products</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'product-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		// 'id',
		array(
			'name'=>'id',
			'value'=>'$data->id',
		), 
		'name',
		'status',
		'sn',
		'upc',
		'quantity',
		// 'model',		
		'price',
		// 'date_added',
		'last_modified',
		'date_available',
		// 'tax_class_id',
		'manufacturer_id',
		// 'description',
		'viewed',
		'ordered',
		array(
			'class'=>'CButtonColumn',
			'template'=>'{delete}{update}', 
			'buttons'=>array(
				'update'=>array(
					'click'=>'function(e){
						
						e.preventDefault();
						
						// find id
						var href = $(this).prop("href");
						var id = href.match(/id\/\d+/);
						id = id[0].replace("id/", "");
						
						// show edit product dialog
						$("#product-form").data("id", id);
						$("#product-form").dialog("open");
						
					}',
				), 
			), 
		),
	),
)); ?>
