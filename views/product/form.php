<form class="pure-form pure-form-aligned">
	<fieldset>
		<!-- id -->
		<input type="hidden" name="id" value="<?php echo $product->id ?>" />
	
		<!-- product name -->
		<div class="pure-control-group">
			<label for="product-name">name </label>
			<input type="text" id="product-name" name="name" value="<?php echo $product->name ?>" required />
			
			<label for="product-status" class="pure-checkbox">
				<input type="checkbox" id="product-status" name="status" <?php echo (is_null($product->status) || $product->status)? 'checked': '' ?> /> Enable
			</label>
		</div>
		
		<!-- date available -->
		<div class="pure-control-group">
			<label for="product-date-available">date available </label>
			<?php $date_available = (empty($product->date_available))? date('m/d/Y') : $product->date_available ?>
			<span id="ui-product-date-available"><?php echo $date_available ?></span>
			<input type="hidden" id="product-date-available" name="date_available" value="<?php echo $date_available ?>" /><br />
		</div>
	
		<!-- serial number -->
		<div class="pure-control-group">
			<label for="product-sn">serial no. </label>
			<input type="text" id="product-sn" name="sn" value="<?php echo $product->sn ?>" /><br />
		</div>
	
		<!-- UPC -->
		<div class="pure-control-group">
			<label for="product-upc">UPC </label>
			<input type="text" id="product-upc" name="upc" value="<?php echo $product->upc ?>" /><br />
		</div>
		
		<!-- price -->
		<div class="pure-control-group">
			<label for="product-price">price </label>
			<input type="text" id="product-price" name="price" value="<?php echo $product->price ?>" /><br />
		</div>
		
		<!-- quantity -->
		<div class="pure-control-group">
			<label for="product-quantity">quantity </label>
			<input type="text" id="product-quantity" name="quantity" value="<?php echo $product->quantity ?>" /><br />
		</div>
		
		<!-- Spec -->
		<h3>Spec</h3>
		<?php foreach ($specList as $key => $spec): ?>
			<div class="pure-control-group">
				<label for="product-spec-<?php echo $spec->name ?>"><?php echo $spec->name ?></label>
				<input type="text" id="product-spec-<?php echo $spec->name ?>" name="spec[]" value="<?php echo $specValue[$key] ?>" /><br />
			</div>
		<?php endforeach ?>
		
		<!-- category -->
		<div class="pure-control-group">
			<label for="product-category">category</label>
			<ul id="product-category">
			<?php foreach ($product->productCategoryRefs as $key => $ref): ?>
				<li data-id="<?php echo $ref->category_id ?>">
					<?php echo $ref->category->name ?>
					<input type="hidden" name="category[]" value="<?php echo $ref->category_id ?>" />
				</li>
			<?php endforeach ?>
			</ul>
			<button type="button" id="show-category-selector">...</button><br />
		</div>
		
		
		<!-- image -->
		<div class="pure-control-group">
			<label for="product-image">image </label>
			<div class="pure-controls">
				<div id="upload-product-image"></div>
				<div id="product-image-thumbnail">
				<?php foreach ($product->productImageRefs as $key => $ref): ?>
					<div class="image-pack">
						<img src="<?php echo CHtml::normalizeUrl(array("image/load", 'id'=>$ref->image_id)) ?>" 
							width="120" alt="product image" />
						<input type="hidden" name="image[]" value="<?php echo $ref->image_id ?>" />
						<div class="remove-image"><i class="icon-close"></i></div>
					</div>
				<?php endforeach ?>
				</div><br />
			</div>
			
		</div>
		
		
		<!-- tags -->
		<div class="pure-control-group">
			<label for="product-tags">tags </label>
			<input type="text" id="product-tags" class="pure-input-1-2" value="<?php echo $product->tags() ?>" name="tags" />
		</div>
		
	
		<!-- description -->
		<div class="pure-control-group">
			<label for="product-description">description </label>
			<textarea name="description" id="product-description" cols="60" rows="8"><?php echo $product->description ?></textarea>
		</div>
		
		
		<h3>SEO </h3>
		<div class="pure-control-group">
			<label for="product-seo-title">title </label>
			<input type="text" id="product-seo-title" name="seo_title" value="<?php echo (!empty($product->productSeos))? $product->productSeos[0]->title: null ?>" />
		</div>
		<div class="pure-control-group">
			<label for="product-seo-description">description </label>
			<input type="text" id="product-seo-description" class="pure-input-1-2" name="seo_description" value="<?php echo (!empty($product->productSeos))? $product->productSeos[0]->description: null ?>" />
		</div>
		<div class="pure-control-group">
			<label for="product-seo-keywords">keywords </label>
			<input type="text" id="product-seo-keywords" class="pure-input-1-2" name="seo_keywords" value="<?php echo (!empty($product->productSeos))? $product->productSeos[0]->keywords: null ?>" />
		</div>
	
		<br />
	
		<hr />
	
		<div class="pure-controls">
			<button type="submit"><?php echo (isset($product->id))? 'update': 'create' ?></button>
			<button type="button" id="close-product-form"><i class="icon-close"></i></button>
		</div>
		
	</fieldset>
</form>