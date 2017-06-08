/* Modifided script from the simple-page-ordering plugin */
jQuery(function($) {

	var select_parent = $('select#parent');
	if( select_parent.val() == -1 ){
		$('body').find('.a3rev_portfolio_panel_container').show();
	}else{
		$('body').find('.a3rev_portfolio_panel_container').hide();
	}

	select_parent.on('change', function(){
		if( $(this).val() == -1 ){
			$('body').find('.a3rev_portfolio_panel_container').show();
		}else{
			$('body').find('.a3rev_portfolio_panel_container').hide();
		}
	});

	$(document).on( "a3rev-ui-onoff_checkbox-switch", '.a3rev_panel_container_on_table_onoff', function( event, value, status ) {
		var tax_id = $(this).attr('data-id');
		if ( status == 'true' ) {
			var pri_navbar = 1;
		} else {
			var pri_navbar = 0;
		}

		var data = {
			action: 'a3_portfolio_update_taxonomy_custom_meta',
			tax_id: tax_id,
			pri_navbar: pri_navbar,
		};
		$.post(ajaxurl, data, function(response) {
			if( response == true || response == 'true'){
			}else{
			}
		});

	});

});
