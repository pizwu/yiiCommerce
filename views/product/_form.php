<?php
/* @var $this ProductController */
/* @var $model Product */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'product-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>64)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sn'); ?>
		<?php echo $form->textField($model,'sn',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'sn'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'upc'); ?>
		<?php echo $form->textField($model,'upc',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'upc'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'quantity'); ?>
		<?php echo $form->textField($model,'quantity'); ?>
		<?php echo $form->error($model,'quantity'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'model'); ?>
		<?php echo $form->textField($model,'model',array('size'=>12,'maxlength'=>12)); ?>
		<?php echo $form->error($model,'model'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'price'); ?>
		<?php echo $form->textField($model,'price',array('size'=>15,'maxlength'=>15)); ?>
		<?php echo $form->error($model,'price'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'date_added'); ?>
		<?php echo $form->textField($model,'date_added'); ?>
		<?php echo $form->error($model,'date_added'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'last_modified'); ?>
		<?php echo $form->textField($model,'last_modified'); ?>
		<?php echo $form->error($model,'last_modified'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'date_available'); ?>
		<?php echo $form->textField($model,'date_available'); ?>
		<?php echo $form->error($model,'date_available'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tax_class_id'); ?>
		<?php echo $form->textField($model,'tax_class_id'); ?>
		<?php echo $form->error($model,'tax_class_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'manufacturer_id'); ?>
		<?php echo $form->textField($model,'manufacturer_id'); ?>
		<?php echo $form->error($model,'manufacturer_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'viewed'); ?>
		<?php echo $form->textField($model,'viewed'); ?>
		<?php echo $form->error($model,'viewed'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ordered'); ?>
		<?php echo $form->textField($model,'ordered'); ?>
		<?php echo $form->error($model,'ordered'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->