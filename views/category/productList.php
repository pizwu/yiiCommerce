<?php foreach ($productRefs as $key => $ref): ?>
<li data-id="<?php echo $ref->product->id ?>">
	<div class="name"><?php echo $ref->product->name ?></div>
	
	<div class="tools">
		<button type="button" class="edit"><i class="icon-pencil"></i>edit</button>
		<button type="button" class="unlink"><i class="icon-link"></i>unlink</button>
		<button type="button" class="delete"><i class="icon-close"></i>delete</button>
	</div>
	
	<!-- image -->
	<?php if (!empty($ref->product->productImageRefs)): ?>
	<div class="image">
		<img src="<?php echo CHtml::normalizeUrl(array("image/load", 'id'=>$ref->product->productImageRefs[0]->image_id)) ?>" 
			width="120" alt="prduct image" />
	</div>
	<?php endif ?>
	
</li>
<?php endforeach ?>