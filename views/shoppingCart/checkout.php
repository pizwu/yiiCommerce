<h2>Checkout</h2>

<?php if (empty($shoppingCart)): ?>
	您還沒有選購任何商品歐，<a href="<?php echo CHtml::normalizeUrl(array("shop/productListInCategory")) ?>">回去逛逛吧！</a>
<?php else: ?>
<form id="checkout" method="post" action="<?php echo CHtml::normalizeUrl(array("shoppingCart/checkout")) ?>">

	<div id="accordion">
	
		<!-- <h3>Billing Info</h3>
		<div>
			Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laborum, sapiente, officia, ea, autem labore quam dolores facere repellat nisi voluptatem dolore blanditiis minus illum veniam.
		</div> -->
	
		<h3>Shipping Info</h3>
		<div id="shipping-info">
			
			<?php if (!empty($addressBooks)): ?>
				<!-- AddressBook -->
				<div class="address-book-selection">
					<label>Address Book</label>
					<select name="shipping[address_book_id]" id="shipping-address-book">
					<?php foreach ($addressBooks as $key => $addressBook): ?>
						<option value="<?php echo $addressBook->id ?>">
							<?php echo $addressBook->first_name.' '.$addressBook->last_name.' - '.$addressBook->street_address.' '.$addressBook->suburb.', '.$addressBook->city.' '.
										$addressBook->state.', '.$addressBook->country.' - '.$addressBook->postcode ?>
						</option>
					<?php endforeach ?>
					</select>
				</div>
			<?php endif ?>
			
			
			<input type="hidden" value="<?php echo $addressBooks[0]->id ?>" name="shipping[address_book_id]" />
			
			<h4>Company Info</h4>
			<label for="shipping-company">Company</label>
			<input type="text" id="shipping-company" name="shipping[company]" value="<?php echo $addressBooks[0]->company ?>" /><br />
		
			<label for="shipping-first-name">First name *</label>
			<input type="text" id="shipping-first-name" name="shipping[first_name]" value="<?php echo $addressBooks[0]->first_name ?>" required /><br />
		
			<label for="shipping-last-name">Last name *</label>
			<input type="text" id="shipping-last-name" name="shipping[last_name]" value="<?php echo $addressBooks[0]->last_name ?>" required /><br />
			
			<label for="shipping-phone">Phone *</label>
			<input type="phone" id="shipping-phone" name="shipping[phone]" value="<?php echo $addressBooks[0]->phone ?>" required /><br /><br />
		
			<h4>Address Info</h4>
			<label for="shipping-street-address">Street address *</label>
			<input type="text" id="shipping-street-address" name="shipping[street_address]" value="<?php echo $addressBooks[0]->street_address ?>" size="80" required /><br />
		
			<label for="shipping-postcode">Post code *</label>
			<input type="text" id="shipping-postcode" name="shipping[postcode]" value="<?php echo $addressBooks[0]->postcode ?>" required /><br />
		
			<label for="shipping-city">City *</label>
			<input type="text" id="shipping-city" name="shipping[city]" value="<?php echo $addressBooks[0]->city ?>" required /><br />
		
			<!-- <label for="shipping-state">State</label>
			<input type="text" id="shipping-state" name="shipping[state]" value="<?php echo $addressBooks[0]->state ?>" /><br /> -->
		
			<label for="shipping-country">Country *</label>
			<input type="text" id="shipping-country" name="shipping[country]" value="<?php echo $addressBooks[0]->country ?>" required /><br />
		</div>
	
		<h3>Shipping Method</h3>
		<div>
			<input type="radio" id="shipping-method-cod" name="shipping_method" value="cod" checked /><label for="shipping-method-cod">貨到付款</label><br />
			<label for="shipping-comment">提醒事項</label><br />
			<textarea name="comment" id="shipping-comment" cols="60" rows="6"></textarea>
		</div>
	
		<!-- <h3>Payment Info</h3>
		<div>
			Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laborum, sapiente, officia, ea, autem labore quam dolores facere repellat nisi voluptatem dolore blanditiis minus illum veniam.
		</div> -->
	
		<h3>Order Review</h3>
		<div class="cart">
			<ul>

				<li class="title">
					<div class="image span-4">產品</div>
					<div class="detail span-4">&nbsp;</div>
					<div class="price span-4">單價</div>
					<div class="quantity span-4">數量</div>
					<div class="total span-4 last">總價</div>
					<div class="clear"></div>
				</li><!-- title -->

				<?php
				foreach ($shoppingCart as $key => $productInCart) {

					$product = $productInCart->product;
					$imageLink = CHtml::normalizeUrl(array("image/load", 'id'=>$product->productImageRefs[0]->image_id));

					$list = <<<EOD
					<li class="product">
						<div class="image span-4">
							<img src="$imageLink" width="140" alt="product image" />
						</div>

						<div class="detail span-4">
							<div class="name">$product->name</div>
						</div>

						<div class="price span-4">$ $product->price</div>

						<div class="quantity span-4">x $productInCart->quantity</div>

						<div class="total span-4 last">$ $productInCart->final_price</div>

						<div class="clear"></div>
					</li>
EOD;
					echo $list;
				}
				?>

			</ul><!-- cart -->
		</div>
	
	</div><!-- checkout -->
	
	<div class="error-message error hide">
		Please filled all * fields
	</div>

	<div class="checkout-button">
		<button type="submit" id="submit-checkout">Submit</button>
	</div><!-- checkout button -->

</form>

<script type="text/javascript" charset="utf-8">
	$(document).ready(function(){
		$('#accordion').accordion({
			heightStyle: "content"
		});
		
		$('#checkout').validate({
			ignore: [], 
			invalidHandler: function(event, validator) {
				$('.error-message').show();
			}
		});
	});
</script>
<?php endif ?>
