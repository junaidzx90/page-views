jQuery(function( $ ) {
	'use strict';

	$(document).on("click", ".pv_nextpage", function(){
		let next_id = $(this).data("next");
		let post_id = $(this).data("id");
		let nextPageUrl = null;

		$.ajax({
			type: "get",
			url: pv_ajax.ajaxurl,
			data: {
				action: "send_to_the_next_page",
				data: {
					next: next_id,
					post: post_id
				}
			},
			dataType: "json",
			success: function (response) {
				nextPageUrl = response.success;
			}
		});

		let timer = 0;
		let interval = window.setInterval(() => {
			timer++;
			$(".pv_timer").html(`<strong>Please wait</strong> ${timer}`);
			if(timer === parseInt(pv_ajax.timer)){
				clearInterval(interval);
				timer = 0;
				if(nextPageUrl !== null){
					window.location.href = nextPageUrl
				}
			}
		}, 0);
		
	})

});
