jQuery(document).ready(function () {

	jQuery('.remove_portfolio_item').on('click', function(){
		var portfolioid = jQuery(this).attr('data-id');
		var data = {
			action: 'a3_portfolio_remove_cookie',
			portfolio_id: portfolioid,
			lang: a3_portfolio_widgets_script_params.lang
		};
		jQuery('body').find('.blockui-waiting').fadeIn();
		jQuery.post( a3_portfolio_widgets_script_params.ajax_url, data, function(response) {
			jQuery('.widget_portfolio_recently_viewed').find('.portfolio_recently_item_'+response).remove();
			if(jQuery('.portfolio_recently_viewed_container').find('.portfolio_recently_item').length <= 0 ){
				jQuery('.portfolio_recently_viewed_container').html( a3_portfolio_widgets_script_params.no_porfolio_text );
			}
			jQuery('body').find('.blockui-waiting').fadeOut();
		});
	});

	jQuery('.clear_all_portfolio_recently').on('click', function(){
		var data = {
			action: 'a3_portfolio_remove_all_cookie',
			lang: a3_portfolio_widgets_script_params.lang
		};
		jQuery('body').find('.blockui-waiting').fadeIn();
		jQuery.post( a3_portfolio_widgets_script_params.ajax_url, data, function(response) {
			if( response ){
				jQuery('.portfolio_recently_viewed_container').html( a3_portfolio_widgets_script_params.no_porfolio_text );
				jQuery('body').find('.blockui-waiting').fadeOut();
			}
		});
	});

});