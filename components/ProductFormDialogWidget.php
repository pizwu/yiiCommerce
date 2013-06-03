<?php  
class ProductFormDialogWidget extends CWidget {
	
	public $basePath;
	
	public function run(){
		
		$formUrl = CHtml::normalizeUrl(array("product/form"));
		$imageUploadUrl = CHtml::normalizeUrl(array("image/upload"));
		$imagePackHTMLUrl = CHtml::normalizeUrl(array("product/imagePackHTML"));
		$productSaveUrl = CHtml::normalizeUrl(array("product/save"));
		$loadProductListUrl = CHtml::normalizeUrl(array("category/loadProductList"));
		$printCategorySelectorUrl = CHtml::normalizeUrl(array("product/printCategorySelector"));
		
		$html = <<<EOD
		
		<div id="product-form" data-id="">
			<div class="loading-message text-center">
				loading<br />
				<img src="$this->basePath/css/image/ajax-loader-big.gif" width="56" alt="loading" />
			</div>
		</div><!-- product form -->
		
		<div id="category-selector">
			<div class="loading-message text-center">
				loading<br />
				<img src="$this->basePath/css/image/ajax-loader-big.gif" width="56" alt="loading" />
			</div>
		</div><!-- category selector -->
		
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function(){
				
				// product dialog
				$('#product-form').dialog({
					autoOpen: false, 
					modal: true, 
					minHeight: 400, 
					minWidth: 660, 
					title: 'Create Product', 
					closeOnEscape: false, 
					position: {
						my: 'top-120%', 
						at: 'center'
					}, 
					open: function(event, ui){
						$(this).find('.loading-message').show();

						// load form from server
						var productForm = $('#product-form');
						// var action = $(this).dialog('option', 'action');
						// var id = (action=='create')? null: $('#product-list .selected').data('id');
						var id = productForm.data('id');
						var action = (id==null)? 'create' : 'edit';
						$.post('$formUrl', { id: id }, function(data, textStatus, xhr) {

							// hide loading message
							productForm.find('.loading-message').hide();

							// load form
							productForm.append(data);

							// create: load category selection from category list
							if(action=='create'){

								// filled category with selected
								var selected = $('#category-list li.selected');
								var category_id = selected.data('id');
								var category_name = selected.find('.name').eq(0).text();
								productForm.find('#product-category').append(
									'<li data-id="'+category_id+'">'+
										category_name+
										'<input type="hidden" name="category[]" value="'+category_id+'" />'+
									'</li>'
								);

							}

							// sortable image
							$('#product-image-thumbnail').sortable();

							// initial date picker
							$('#product-date-available').datepicker({
								showOn: "button", 
								dateFormat: "mm/dd/yy"
							});
							$('#product-date-available').change(function(){
								var date = $(this).val();
								$(this).prev().text(date);
							});

							// fine uploader
							$('#upload-product-image').fineUploader({
								request: {
									endpoint: '$imageUploadUrl'
								}, 
								validation: {
									allowedExtensions: ['jpeg', 'jpg', 'png'], 
									sizeLimit: 10*1024*1024
								}, 
								text: {
									uploadButton: 'upload'
								}
							}).on('complete', function(event, id, fileName, responseJSON){
								if (responseJSON.success) {

									var mainImageSet = ($('#product-image-thumbnail > .image-pack').length==0)? false: true;

									// load image-pack HTML
									$.post('$imagePackHTMLUrl', 
									{ id: responseJSON.id }, function(data, textStatus, xhr) {

										// append image-pack
										$('#product-image-thumbnail').append(data);

										// first image setting main image
										if(!mainImageSet){

											var imageId = $('.image-pack').eq(0).find('.image-id').val();

											// setting main image id
											$('#main-image-recorder').val(imageId);

											// setting main image tag
											$('.image-pack').eq(0).addClass('main').prepend('<div class="main-image-tag">main</div>');

										}

									}, 'html');
								}
							});

						}, 'html');
					}, 
					close: function(event, ui){
						$(this).find('form').remove();
					}
				});

				// category selector
				$('#category-selector').dialog({
					autoOpen: false, 
					modal: true, 
					minHeight: 200, 
					minWidth: 480, 
					title: 'Select Product Categories', 
					open: function(event, ui){
						$(this).find('.loading-message').show();
					}, 
					close: function(event, ui){
						$(this).find('#category-selector-list').remove();
					}
				});

				// product form actions
				var ProductForm = Backbone.View.extend({
					initialize: function(){
						console.log('initialize product form');
					}, 
					el: $('#product-form'), 
					events: {
						// product form
						"click #close-product-form": "closeProductForm", 
						"submit form": "saveProduct", 

						// category selector
						"click #show-category-selector": "showCategorySelector", 

						// image
						"click .image-pack .remove-image": "removeImage", 
						'click .image-pack': 'setMainImage'
					}, 
					closeProductForm: function(e){
						$('#product-form').dialog('close');
					}, 
					// Save: create / update product
					saveProduct: function(e){

						e.preventDefault();

						// validate form
						if($(this.el).find('form').valid()){

							var serializeString = $(this.el).find('form').serialize();

							// save to server
							$.post('$productSaveUrl', serializeString
							, function(data, textStatus, xhr) {

								// reload product list
								var category_id = $('#category-list li.selected').data('id');
								$.post('$loadProductListUrl', { category_id: category_id }, function(data, textStatus, xhr) {

									$('#product-list').html(data);

								}, 'html');

								// close form
								$('#product-form').dialog('close');

							}, 'json');
						}

					}, 
					showCategorySelector: function(e){

						var categorySelector = $('#category-selector');
						categorySelector.dialog('open');

						// load category selector
						var selectedCategoryIdArray = [];
						$('#product-category li').each(function(index, element){
							selectedCategoryIdArray.push($(element).data('id'));
						})

						$.post('$printCategorySelectorUrl', {
							selectedCategoryIdArray: selectedCategoryIdArray
						}, function(data, textStatus, xhr) {

							// hide loading message
							categorySelector.find('.loading-message').hide();

							// load category selector
							categorySelector.append(data);

						}, 'html');

					}, 
					removeImage: function(e){
						$(e.currentTarget).parent().remove();
					}, 
					setMainImage: function(e){

						// switch main class, main tag on image-pack
						$(this.el).find('.image-pack.main').removeClass('main')
							.find('.main-image-tag').remove();
						$(e.currentTarget).addClass('main')
							.prepend('<div class="main-image-tag">main</div>');

						// record in hidden input
						var image_id = $(e.currentTarget).find('input.image-id').val();
						$('#main-image-recorder').val(image_id);

					}
				});
				var productForm = new ProductForm;

				var CategorySelector = Backbone.View.extend({
					initialize: function(){
						console.log('initialize category selector');
					}, 
					el: $('#category-selector'), 
					events: {
						"click li": "toggleSelection", 
						"click #cancel-select-category": "closeSelector", 
						"click #select-category": "selectCategory"
					}, 
					toggleSelection: function(e){

						e.stopPropagation();
						$(e.currentTarget).toggleClass('selected');

					}, 
					closeSelector: function(e){

						$(this.el).dialog('close');

					}, 
					selectCategory: function(e){

						var selection = [];
						$(this.el).find('li.selected').each(function(index, element){
							selection.push({
								id: $(element).data('id'), 
								name: $(element).find('.name').eq(0).text()
							});
						});

						var productCategory = $('#product-category');
						productCategory.html('');
						$.each(selection, function(index, element){
							productCategory.append(
								'<li data-id="'+element.id+'">'+element.name+
									'<input type="hidden" name="category[]" value="'+element.id+'" />'+
								'</li>'
							);
						});

						$(this.el).dialog('close');

					}
				});
				var categorySelector = new CategorySelector;
				
			});
		</script>
		
EOD;

		echo $html;
		
	}  
	
}
?>