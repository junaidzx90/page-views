jQuery(function( $ ) {
	'use strict';

	$('#visitable_pages').selectize({
		placeholder: "Select a page",
		plugins: ['remove_button'],
		valueField: 'id',
		labelField: 'title',
		searchField: ['title'],
		closeAfterSelect: true,
		onInitialize: function(){
			let selectize = this;
			$.ajax({
				type: "get",
				url: pageviews.ajaxurl,
				data: {
					action: "get_saved_pages"
				},
				dataType: "json",
				success: function (response) {
					let data = response.success;
					selectize.addOption(data)
					let selectedItems = [];
					$.each(data, function(i, obj){
						selectedItems.push(obj.id)
					})
					selectize.setValue(selectedItems)
				}
			});
		},
		load: function(input, callback) {
			if (!input.length) return callback();
			$.ajax({
				url: pageviews.ajaxurl,
				method: 'GET',
				data: {
					action: "get_page_views_search_val",
					query : input
				},
				error: function() {
					callback();
				},
				dataType: "json",
				success: function (result) {
					if(result.success){
						if (result.success.length > 10) {
							callback(result.success.slice(0, 10));
						}else{
							callback(result.success);
						}
					}
				}
			});
		}
	});

	$('#pv_filter').keyup(function(){
		var value = $(this).val().toLowerCase();
		$('.pv_code').filter(function(){
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
		});
	});

});
