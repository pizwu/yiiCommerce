<?php	
	$this->widget('zii.widgets.CBreadcrumbs', array(
	    'links'=>$breadcrumbs,
		'homeLink'=> CHtml::link('Root', array('shop/productListInCategory')), 
	));
?>

<ul id="category-list">
	category<br />
<?php
	foreach ($childCategories as $key => $childCategory) {
		echo "<a href='".CHtml::normalizeUrl(array("shop/productListInCategory", 'id'=>$childCategory->id))."'>";
			echo "<li>".$childCategory->name."</li>";
		echo "</a>";
	}
?>
</ul><!-- category list -->

<ul id="product-list">
	product<br />
<?php
	foreach ($products as $key => $product) {
		$imageId = $product->productImageRefs[0]->image_id;
		$productLink = CHtml::normalizeUrl(array("shop/product", 'id'=>$product->id));
		$imageUrl = CHtml::normalizeUrl(array("image/load", 'id'=>$imageId));
		
		$html = <<<EOD
		<li data-id="$product->id">
			<a href="$productLink">
				$product->name<br />
			</a>
			<button type="button" class="add-to-shopping-cart">+shopping cart</button>
			<img src="$imageUrl" alt="product image" width="120" />
		</li>
EOD;
		
		echo $html;
		
	}
?>
</ul><!-- product list -->

<script type="text/javascript" charset="utf-8">
	var Product = Backbone.View.extend({
		initialize: function(){
			console.log('initialize product');
		}, 
		el: $('#product-list'), 
		events: {
			"click button.add-to-shopping-cart": "addToShoppingCart"
		}, 
		addToShoppingCart: function(e){

			var product_id = $(e.currentTarget).parent().data('id');

			$.post('<?php echo CHtml::normalizeUrl(array("shoppingCart/add")) ?>', 
			{ id: product_id, quantity: 1 }, function(data, textStatus, xhr) {

				alert("product added to shopping cart");

			}, 'json');

		}
	});
	var product = new Product;
</script>