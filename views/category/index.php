<!-- Category block -->
<div class="span-16">
	<div class="title">Category list - </div>
	<div id="category-tools">
		<button id="expend-all">Expend All</button>
		<button id="collapse-all">Collapse All</button>
	</div>
	<ul id="category-list">
		<li class="root" data-id="0">
			<ul>
			<?php if (empty($root)): ?>
				<div class="empty-message">empty category, right click to create one.</div>	
			<?php else: ?>
				<?php
					foreach ($root as $key => $category) {
						echo $this->listToHtml($category);
					}
				?>
			<?php endif ?>
			</ul>
		</li>

	</ul>

	<ul id="category-menu" class="hide">
		<li id="show-add-root-category-form">+ Root Category</li>
		<li id="show-add-category-form">+ Category</li>
		<hr />
		<li id="show-add-product-form">+ Product</li>
		<hr />
		<li id="rename-category">Rename</li>
		<li id="delete-category">- Category</li>
	</ul>
</div>

<!-- Category script -->
<script type="text/javascript" charset="utf-8">

	// sortable list
	var sortableCategory = function(){
		$('#category-list ul').sortable({
			connectWith: "#category-list ul", 
			placeholder: "placeholder", 
			forcePlaceholderSizeType: true, 
			receive: function(){
				
				var self = this;
				
				var idOrder = $(this).sortable("toArray", { attribute: "data-id" });
				var parent_id = $(this).parent().data('id');

				// console.log(idOrder);
				// console.log(parent_id);
				
				// update order
				$.post('<?php echo CHtml::normalizeUrl(array("category/resort")) ?>', 
				{ idOrder: idOrder, parent_id: parent_id }, function(data, textStatus, xhr) {
					
					// originally, it's an empty category
					if(idOrder.length == 1 && $(self).parent().find('.open-close-indicator').not('.collapse') && $(self).parent().find('.open-close-indicator').not('.expend')){
						// so we give it an indicator
						$(self).parent().find('.open-close-indicator').eq(0).addClass('expend');
					}
					
					// if originally, it's not an empty category, it's just not expend yet
					if(idOrder.length == 1 && $(self).parent().children('.open-close-indicator').is('.collapse')){
						console.log('in');
						// so now, we need to expend it, by hand
						$(self).find('li').remove();
						$(self).parent().dblclick();
					}
					
				}, 'json');
				
			}, 
			stop: function(){
				// update order
				var idOrder = $(this).sortable("toArray", { attribute: "data-id" });
				var parent_id = $(this).parent().data('id');
				
				// if list is empty, remove collapse / expend indicator
				if(idOrder.length == 0){
					$(this).parent().find('.open-close-indicator').eq(0).removeClass('collapse').removeClass('expend');
				}
				else{
					
					// update order
					$.post('<?php echo CHtml::normalizeUrl(array("category/resort")) ?>', 
					{ idOrder: idOrder, parent_id: parent_id }, function(data, textStatus, xhr) {
						// do nothing
					}, 'json');
					
				}
				
			}
		});
	};
	
	$(document).ready(function(){
		
		sortableCategory();
		
		// category tools
		var CategoryTools = Backbone.View.extend({
			initialize: function(){
				console.log('initialize category tools');
			}, 
			el: $('#category-tools'), 
			events: {
				"click #expend-all": "expendAllCategory", 
				"click #collapse-all": "collapseAllCategory"
			}, 
			expendAllCategory: function(e){
				
				categoryList.status.expendAll = true;
				var categoryListTag = $('#category-list');
				categoryListTag.find('.collapse').parent().dblclick();
				
			}, 
			collapseAllCategory: function(e){
				
				var categoryListTag = $('#category-list');
				categoryListTag.find('.expend').parent().dblclick();
				
			}
		});
		var categoryTools = new CategoryTools;
		
		// category list
		var CategoryList = Backbone.View.extend({
			initialize: function(){
				console.log('initialize category list');;
				this.status = {};
				this.status.showMenu = false;
				this.status.expendAll = false;
				this.categoryId = null;
			}, 
			el: $('#category-list'), 
			events: {
				
				// menu
				"contextmenu": "showMenu", 
				"contextmenu li": "showMenu", 
				"click": "closeMenu", 
				
				// category form
				"submit .category-form": "createCategory", 
				"click .cancel-category-form": "closeCategoryForm", 
				
				// rename category form
				"submit .rename-category-form": "renameCategory", 
				"click .cancel-rename-category-form": "closeRenameCategoryForm", 
				
				// list actions
				"dblclick li": "toggleList", 
				"click li > .open-close-indicator": "toggleListByIndicator", 
				
				// product list
				"click li": "showProductInCategory"
				
			}, 
			showMenu: function(e){
				
				e.preventDefault();
				e.stopPropagation();
				
				$(this.el).removeClass('selected').find('.selected').removeClass('selected');
				var target = ($(e.currentTarget).is('#category-list'))? $(e.currentTarget).find('li').eq(0) : $(e.currentTarget);
				target.addClass('selected');
				
				// show menu
				$('#category-menu').show().css({
					left: e.pageX, 
					top: e.pageY
				});
				
				// menu list selection
				// root show only "+root category"
				if(target.is('.root')){
					$('#category-menu li').hide();
					$('#show-add-root-category-form').show();
				}
				else{
					$('#category-menu li').show();
				}
				
				this.status.showMenu = true;
				
			}, 
			closeMenu: function(e){
				
				if(this.status.showMenu){
					$('#category-menu').hide();
					this.status.showMenu = false;
				}
				
			}, 
			createCategory: function(e){
				
				e.preventDefault();
				
				var self = this;
				
				var categoryForm = $(e.currentTarget);
				var name = categoryForm.find('input').val();
				var parent = (categoryForm.parent().data('id')!=undefined)? categoryForm.parent() : categoryForm.parent().parent();
				var parent_id = (categoryForm.parent().data('id')!=undefined)? categoryForm.parent().data('id'): categoryForm.parent().parent().data('id');
				
				$.post('<?php echo CHtml::normalizeUrl(array("category/create")) ?>', 
				{ name: name, parent_id: parent_id }, function(data, textStatus, xhr) {
					
					// clear form, then hide it
					categoryForm.find('input').val('');
					categoryForm.remove();
					
					// root, just append list to bottom
					if(parent.children('.open-close-indicator').length == 0){

						// append list item to bottom
						parent.find('ul').eq(0).append(data);
						
					}
					// the empty category, + first sub-category
					else if(!(parent.find('.open-close-indicator').eq(0).is('.collapse') || parent.find('.open-close-indicator').eq(0).is('.expend'))){
						
						// +expend class
						parent.children('.open-close-indicator').eq(0).addClass('expend');
						
						// append list item to bottom
						parent.find('ul').eq(0).append(data);
						
					}
					else{
						
						// if empty list, we just expend the list
						if(parent.find('ul').eq(0).find('li').length == 0){
							parent.dblclick();
						}
						// if list loaded before, just append list item to bottom, and expend it if collapse
						else{
							parent.find('ul').eq(0).append(data);
						}

					}
					
				}, 'html');
				
				
			}, 
			closeCategoryForm: function(e){
				
				var categoryForm = $('.category-form');
				var ul = categoryForm.parent();
				categoryForm.find('input').val('');
				categoryForm.remove();
				
				// if no sub-category, remove ul
				if(ul.children().length == 0)
					ul.remove();
			}, 
			renameCategory: function(e){
				
				e.preventDefault();
				
				var form = $(e.currentTarget);
				var target = form.prev();
				var id = form.data('id');
				var name = form.find('input').val();
				
				$.post('<?php echo CHtml::normalizeUrl(array("category/rename")) ?>', { id: id, name: name }, function(data, textStatus, xhr) {
					
					target.show().text(data.name);
					form.remove();
					
				}, 'json');
				
				
			}, 
			closeRenameCategoryForm: function(e){
				
				var form = $(e.currentTarget).parent();
				form.prev().show();
				form.remove();
				
			}, 
			toggleList: function(e){
				
				e.stopPropagation();
				var self = this;
				
				// expend list
				if($(e.currentTarget).find('.open-close-indicator').eq(0).is('.collapse')){
					
					// if child category already exists, just show it
					if($(e.currentTarget).find('ul').length > 0 && $(e.currentTarget).find('ul').eq(0).find('li').length > 0){
						
						// show child category
						$(e.currentTarget).find('ul').eq(0).show();
						
						// change indicator
						$(e.currentTarget).find('.open-close-indicator').eq(0).removeClass('collapse').addClass('expend');
						
					}
					// else, load child category
					else{
						
						// loading animation
						$(e.currentTarget).find('.open-close-indicator').eq(0).removeClass('collapse').html(
							'<img src="<?php echo $this->module->assetsUrl ?>/css/image/ajax-loader.gif" width="16" alt="loading">'
						);

						var id = $(e.currentTarget).data('id');
						$.post('<?php echo CHtml::normalizeUrl(array("category/expendCategory")) ?>', 
						{ id: id }, function(data, textStatus, xhr) {
							
							// remove animation, add class: expend
							$(e.currentTarget).find('.open-close-indicator').eq(0).addClass('expend').find('img').remove();

							// load child categories
							$(e.currentTarget).find('ul').eq(0).append(data);
							
							// sortable
							sortableCategory();
							
							// if: expend all, check again
							if($('#category-list .collapse').length > 0 && self.status.expendAll)
								$('#category-list .collapse').parent().dblclick();
							else
								self.status.expendAll = false;

						}, 'html');
						
					}
										
				}
				// fold list
				else if($(e.currentTarget).find('.open-close-indicator').eq(0).is('.expend')){
					
					// hide children
					$(e.currentTarget).find('ul').eq(0).hide();
					
					// change indicator
					$(e.currentTarget).find('.open-close-indicator').eq(0).removeClass('expend').addClass('collapse');
					
				}
				// do nothing
				else{
					
				}
				
			}, 
			toggleListByIndicator: function(e){
				
				e.stopPropagation();
				
				$(e.currentTarget).parent().dblclick();
			}, 
			showProductInCategory: function(e){

				e.stopPropagation();
				
				$(this.el).find('.selected').removeClass('selected');
				$(e.currentTarget).addClass('selected');

				// if category id changed, reload product list
				if(this.categoryId != $(e.currentTarget).data('id')){

					// renew category id
					this.categoryId = $(e.currentTarget).data('id');

					// reload product list
					var category_id = this.categoryId;
					$.post('<?php echo CHtml::normalizeUrl(array("category/loadProductList")) ?>', 
					{ category_id: category_id }, function(data, textStatus, xhr) {

						$('#product-list').html(data);

					}, 'html');

				}

			}
		});
		var categoryList = new CategoryList;
		
		// category menu
		var CategoryMenu = Backbone.View.extend({
			initialize: function(){
				console.log('initialize category menu');
				
				this.addCategoryForm = $(
					'<form class="category-form">'+
						'<input type="text" size="16" />'+
						'<button type="submit" class="create-category">create</button>'+
						'<button type="button" class="cancel-category-form"><i class="icon-close"></i></button>'+
					'</form>'
				);
				
				this.renameCategoryForm = $(
					'<form class="rename-category-form" data-id="">'+
						'<input type="text" size="16" value="" />'+
						'<button type="submit" class="rename-category">rename</button>'+
						'<button type="button" class="cancel-rename-category-form"><i class="icon-close"></i></button>'+
					'</form>'
				);
			}, 
			el: $('#category-menu'), 
			events: {
				"click #show-add-root-category-form": "showAddRootCategoryForm", 
				"click #show-add-category-form": "showAddCategoryForm", 
				
				"click #rename-category": "showRenameCategoryForm", 
				"click #delete-category": "deleteCategory", 
				
				"click #show-add-product-form": "showAddProductForm"
			}, 
			showAddRootCategoryForm: function(e){
				var categoryList = $('#category-list');
				categoryList.find('.selected').removeClass('selected');
				categoryList.find('li').eq(0).addClass('selected');
				this.showAddCategoryForm(null);
			}, 
			showAddCategoryForm: function(e){
				// hide menu
				$(this.el).hide();
				
				// show form
				var target = ($('#category-list').is('.selected'))? $('#category-list') : $('#category-list .selected');
				if(target.is('ul'))
					target.append(this.addCategoryForm);
				else{
					
					// find ul children, or create one
					if(target.find('ul').length == 0){
						target.append('<ul></ul>');
					}
					
					target.find('ul').eq(0).append(this.addCategoryForm);
				}
				
				// if list already loaded, just expend it
				if(target.find('ul').eq(0).find('li').length > 0 && target.find('.open-close-indicator').eq(0).is('.collapse'))
					target.dblclick();
				
				// auto focus
				target.find('.category-form input').focus();
			}, 
			showRenameCategoryForm: function(e){
				
				var selected = $('#category-list .selected');
				var name = selected.find('.name').eq(0).text();
				
				// hide menu
				$(this.el).hide();
				
				// hide original content
				$('#category-list .name').show();
				selected.find('.name').eq(0).hide();
				
				// show form
				var id = selected.data('id');
				selected.find('.name').eq(0).after(this.renameCategoryForm).next().data('id', id).find('input').val(name).focus();
				
			}, 
			deleteCategory: function(e){
				
				$(this.el).hide();
				
				e.stopPropagation();
				
				var selectedItem = $('#category-list li.selected');
				var name = selectedItem.find('.name').eq(0).text();
				var ul = selectedItem.parent();
				var id = selectedItem.data('id');
				
				var result = confirm('Do you really want to delete category: "'+name+'" ?');
				if(result){
					// remove item
					$.post('<?php echo CHtml::normalizeUrl(array("category/delete")) ?>', { id: id }, function(data, textStatus, xhr) {

						// remove this list item
						selectedItem.remove();
						if(ul.children().length == 0)
							ul.remove();

					}, 'json');
				}
				
			}, 
			showAddProductForm: function(e){
				
				$(this.el).hide();
				
				// show dialog
				var productForm = $('#product-form');
				productForm.dialog('option', 'title', 'Create Product').dialog('option', 'action', 'create').dialog('open');
				
			}
		});
		var categoryMenu = new CategoryMenu;
	});
</script>

<div id="product-form">
	<div class="loading-message text-center">
		loading<br />
		<img src="<?php echo $this->module->assetsUrl ?>/css/image/ajax-loader-big.gif" width="56" alt="loading" />
	</div>
</div>

<div id="category-selector">
	<div class="loading-message text-center">
		loading<br />
		<img src="<?php echo $this->module->assetsUrl ?>/css/image/ajax-loader-big.gif" width="56" alt="loading" />
	</div>
</div>

<!-- Product script -->
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
				var action = $(this).dialog('option', 'action');
				var id = (action=='create')? null: $('#product-list .selected').data('id');
				$.post('<?php echo CHtml::normalizeUrl(array("product/form")) ?>', { id: id }, function(data, textStatus, xhr) {
					
					// hide loading message
					productForm.find('.loading-message').hide();
					
					// load form
					productForm.append(data);
					
					// create: load date picker / category selection from category list
					if(action=='create'){
						// initial date picker
						$('#product-date-available').datepicker({
							showOn: "button", 
							dateFormat: "mm/dd/yy"
						});
						$('#product-date-available').change(function(){
							var date = $(this).val();
							$(this).prev().text(date);
						});
						
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
					
					// fine uploader
					$('#upload-product-image').fineUploader({
						request: {
							endpoint: '<?php echo CHtml::normalizeUrl(array("image/upload")) ?>'
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
							$('#product-image-thumbnail').append(
								'<div class="image-pack">'+
									'<img src="<?php echo CHtml::normalizeUrl(array("image/load")) ?>/id/'+responseJSON.id+'" width="120" alt="product-image" />'+
									'<input type="hidden" name="image[]" value="'+responseJSON.id+'" />'+
									'<div class="remove-image"><i class="icon-close"></i></div>'+
								'</div>'	
							);
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
				"click .image-pack .remove-image": "removeImage"
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
					$.post('<?php echo CHtml::normalizeUrl(array("product/save")) ?>', serializeString
					, function(data, textStatus, xhr) {
						
						// reload product list
						var category_id = $('#category-list li.selected').data('id');
						$.post('<?php echo CHtml::normalizeUrl(array("category/loadProductList")) ?>', { category_id: category_id }, function(data, textStatus, xhr) {
							
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

				$.post('<?php echo CHtml::normalizeUrl(array("product/printCategorySelector")) ?>', {
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


<!-- Product block -->
<div class="span-6 last">
	<div class="title">Product</div>
	<ul id="product-list">
	</ul>
</div>

<script type="text/javascript" charset="utf-8">
	var ProductList = Backbone.View.extend({
		initialize: function(){
			console.log('initialize product list');
		}, 
		el: $('#product-list'), 
		events: {
			"click .edit": "editProduct", 
			"click .unlink": "unlinkProductFromCategory", 
			"click .delete": "deleteProduct"
		}, 
		editProduct: function(e){
			
			$(this.el).find('.selected').removeClass('selected');
			$(e.currentTarget).parentsUntil('li').parent().addClass('selected');
			
			// open dialog
			$('#product-form').dialog('option', 'title', 'Edit Product').dialog('option', 'action', 'edit').dialog('open');
			
		}, 
		unlinkProductFromCategory: function(e){
			
			var product_id = $(e.currentTarget).parentsUntil('li').parent().data('id');
			var product_name = $(e.currentTarget).parentsUntil('li').parent().find('.name').text();
			var category = $('#category-list li.selected');
			var category_id = category.data('id');
			var category_name = category.find('.name').text();
			
			var result = confirm("unlink product: \""+product_name+"\" from category: "+category_name+"?");
			if(result){
				
				$.post('<?php echo CHtml::normalizeUrl(array("product/unlinkFromCategory")) ?>', { category_id: category_id, product_id: product_id }, function(data, textStatus, xhr) {
					
					$(e.currentTarget).parentsUntil('li').parent().remove();
					
				}, 'json');
				
			}
			
		}, 
		deleteProduct: function(e){
			
			var name = $(e.currentTarget).parentsUntil('li').parent().find('.name').text();
			var product_id = $(e.currentTarget).parentsUntil('li').parent().data('id');
			var result = confirm("delete product \""+name+"\"?");
			if(result){
				
				$.post('<?php echo CHtml::normalizeUrl(array("product/delete")) ?>', { id: product_id }, function(data, textStatus, xhr) {
					$(e.currentTarget).parentsUntil('li').parent().remove();
				}, 'json');
				
			}
		}
	});
	var productList = new ProductList;
</script>