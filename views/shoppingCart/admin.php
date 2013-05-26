<ul id="cart">
	
	<li class="title">
		<div class="image span-4">產品</div>
		<div class="detail span-6">&nbsp;</div>
		<div class="price span-4">單價</div>
		<div class="quantity span-4">數量</div>
		<div class="total span-4 last">總價</div>
	</li><!-- title -->
	
	<?php
	foreach ($shoppingCart as $key => $productInCart) {
		
		$product = $productInCart->product;
		$imageLink = CHtml::normalizeUrl(array("image/load", 'id'=>$product->productImageRefs[0]->image_id));
		
		$list = <<<EOD
		<li class="product">
			<div class="image span-4 first">
				<img src="$imageLink" width="140" alt="product" />
			</div>

			<div class="detail span-6">
				<div class="name">$product->name</div>
			</div>

			<div class="price span-4">$ $product->price</div>

			<div class="quantity span-4">x $productInCart->quantity</div>

			<div class="total span-4 last">$ $productInCart->final_price</div>
		</li>
EOD;
		echo $list;
	}
	?>
	
</ul><!-- cart -->

<!-- <div class="total">
	<div class="title span-20">total</div>
	<div class="price span-4 last">$ 2569</div>
</div> --><!-- total -->

<div class="buttons">
	<a href="<?php echo CHtml::normalizeUrl(array("shop/checkout")) ?>">checkout</a>
	<a href="<?php echo Yii::app()->request->urlReferrer ?>">continue shopping</a>
</div>