<?php
/* @var $this SpecController */
/* @var $model Spec */

$this->breadcrumbs=array(
	'Specs'=>array('index'),
	'Manage',
);

$this->menu=array(
	// array('label'=>'List Spec', 'url'=>array('index')),
	array('label'=>'Create Spec', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('spec-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Specs</h1>

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
	'id'=>'spec-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		// 'id',
		'name',
		'order',
		array(
			'class'=>'CButtonColumn',
			'template'=>'{up}{down}{delete}{update}', 
			'buttons'=>array(
				'up'=>array(
					'label'=>'<i class="icon-arrow-up"></i>', 
					'url'=>'$data->id', 
					'click'=>'
						function(e){
							e.preventDefault();
							
							var id = $(this).prop("href").match(/spec\/\d+/);
							id = id[0].replace("spec/", "");
							
							// update order
							$.post("'.CHtml::normalizeUrl(array("spec/resortOrder")).'", { id: id, order: "-" }, function(data, textStatus, xhr) {
								// update grid
								$.fn.yiiGridView.update("spec-grid");
							}, "json");
							
						}
					', 
				), 
				'down'=>array(
					'label'=>'<i class="icon-arrow-down"></i>', 
					'url'=>'$data->id', 
					'click'=>'
						function(e){
							e.preventDefault();
							
							var id = $(this).prop("href").match(/spec\/\d+/);
							id = id[0].replace("spec/", "");
							
							// update order
							$.post("'.CHtml::normalizeUrl(array("spec/resortOrder")).'", { id: id, order: "+" }, function(data, textStatus, xhr) {
								// update grid
								$.fn.yiiGridView.update("spec-grid");
							}, "json");
							
						}
					', 
				), 
			), 
		),
	),
)); ?>
