<form>
	<!-- id -->
	<input type="hidden" name="id" value="<?php echo $product->id ?>" />
	
	<!-- product name -->
	<label for="product-name">name </label>
	<input type="text" id="product-name" name="name" value="<?php echo $product->name ?>" required /><br />
	
	<label for="product-status">status</label>
	<input type="checkbox" id="product-status" name="status" <?php echo (is_null($product->status) || $product->status)? 'checked': '' ?> />
	<label for="product-status">Enable</label>
	<br />
	
	<!-- sku number -->
	<label for="product-sku">SKU no. </label>
	<input type="text" id="product-sku" name="sku" value="<?php echo $product->sku ?>" /><br />
	
	<!-- price -->
	<label for="product-price">price </label>
	<input type="text" id="product-price" name="price" value="<?php echo $product->price ?>" /><br />
	
	<!-- quantity -->
	<label for="product-quantity">quantity </label>
	<input type="text" id="product-quantity" name="quantity" value="<?php echo $product->quantity ?>" /><br />
	
	<!-- category -->
	<label for="product-category">category</label>
	<ul id="product-category"></ul>
	<button type="button" id="show-category-selector">...</button><br />
	
	<!-- date available -->
	<label for="product-date-available">date available </label>
	<?php $date_available = (empty($product->date_available))? date('m/d/Y') : $product->date_available ?>
	<span id="ui-product-date-available"><?php echo $date_available ?></span>
	<input type="hidden" id="product-date-available" name="date_available" value="<?php echo $date_available ?>" /><br />
	
	<!-- image -->
	<label for="product-image">image </label>
	<div id="upload-product-image"></div>
	<div id="product-image-thumbnail"></div><br />
	
	<!-- weight -->
	<label for="product-weight">weight </label>
	<input type="text" id="product-weight" name="weight" value="<?php echo $product->weight ?>" /><br />
	
	<!-- url -->
	<label for="product-url">url </label>
	<input type="text" id="product-url" name="url" value="<?php echo $product->url ?>" /><br />
	
	<!-- description -->
	<label for="product-description">description </label><br />
	<textarea name="description" id="product-description" cols="60" rows="8"><?php echo $product->description ?></textarea><br />
	
	<br />
	
	<hr />
	
	<button type="submit">create</button>
	<button type="button" id="close-product-form"><i class="icon-close"></i></button>
</form>