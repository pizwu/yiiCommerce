<?php if (empty($shoppingCart)): ?>
	Your shopping cart is empty, <a href="<?php echo CHtml::normalizeUrl(array("shop/productListInCategory")) ?>">continue shopping.</a>
<?php else: ?>
<ul id="cart" class="container">
	
	<li class="title">
		<div class="image span-4">產品</div>
		<div class="detail span-4">&nbsp;</div>
		<div class="price span-4">單價</div>
		<div class="quantity span-4">數量</div>
		<div class="total span-4">總價</div>
		<div class="tools span-4 last">操作</div>
		<div class="clear"></div>
	</li><!-- title -->
	
	<?php
	foreach ($shoppingCart as $key => $productInCart) {
		
		$product = $productInCart->product;
		$imageLink = CHtml::normalizeUrl(array("image/load", 'id'=>$product->productImageRefs[0]->image_id));
		
		$list = <<<EOD
		<li class="product" data-id="$product->id">
			<div class="image span-4">
				<img src="$imageLink" width="140" alt="product image" />
			</div>

			<div class="detail span-4">
				<div class="name">$product->name</div>
			</div>

			<div class="price span-4">$ $product->price</div>

			<div class="quantity span-4">x $productInCart->quantity</div>

			<div class="total span-4">$ $productInCart->final_price</div>
			
			<div class="tools span-4 last">
				<button type="button" class="delete">x</button>
			</div>
			
			<div class="clear"></div>
		</li>
EOD;
		echo $list;
	}
	?>
	
</ul><!-- cart -->
<script type="text/javascript" charset="utf-8">
	$(document).ready(function(){
		
		var CartView = Backbone.View.extend({
			initialize: function(){
				console.log('initialize CartView');
			},
			el: $('#cart'),
			events: {
				'click .product .delete': 'deleteProduct'
			},
			deleteProduct: function(e){
				
				var product = $(e.currentTarget).parent().parent();
				var id = product.data('id');
				var name = product.find('.name').text();
				var result = confirm('delete product '+name+' from shopping cart?');
				
				if(result){
					
					$.post('<?php echo CHtml::normalizeUrl(array("shoppingCart/delete")) ?>', { id: id }, function(data, textStatus, xhr) {
						
						window.location.reload();
						
					}, 'json');
					
				}
				
			}
		});
		var cartView = new CartView;
		
	});
</script>

<!-- <div class="total">
	<div class="title span-20">total</div>
	<div class="price span-4 last">$ 2569</div>
</div> --><!-- total -->

<div class="buttons">
	<a href="<?php echo CHtml::normalizeUrl(array("shoppingCart/checkout")) ?>">checkout</a>
	<a href="<?php echo Yii::app()->request->urlReferrer ?>">continue shopping</a>
</div>
<?php endif ?>