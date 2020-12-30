// JavaScript Document
jQuery(document).ready(function () {

	jQuery('body').find('.a3-portfolio-single-wrap').each( function() {
		var portfolioId = jQuery(this).find('.a3-portfolio-item-image-container').attr('data-portfolioId');
		var data = {
			action: 'a3_portfolio_set_cookie',
			portfolio_id: portfolioId,
			lang: a3_portfolio_single_script_params.lang
		};
		jQuery.post( a3_portfolio_single_script_params.ajax_url, data, function(response) {
			if( response == true || response == 'true'){
				//console.log('Cookie saved');
			}else{
				//console.log('Cookie not save!');
			}
		});
	});

	jQuery(window).on('load',function()  {
		if(jQuery(".a3-portfolio-single-wrap").width() <= 767 ){
			jQuery(".a3-portfolio-single-wrap").addClass("a3-portfolio-expander-popup-mobile");
		}else{
			jQuery(".a3-portfolio-single-wrap").removeClass("a3-portfolio-expander-popup-mobile");
		}

		var active_larg_img = jQuery(".a3-portfolio-item-image-container").find("img");
		jQuery('.a3-portfolio-item-image-container').find('.a3-portfolio-loading').fadeIn( 400,function () {
			var activate_larg_original = active_larg_img.attr('data-original');
		 	if ( typeof activate_larg_original !== 'undefined' && activate_larg_original !== false ) {
				active_larg_img.attr("src", activate_larg_original );
				active_larg_img.removeAttr("data-original");
			}
			var activate_larg_original_srcset = active_larg_img.attr('data-osrcset');
		 	if ( typeof activate_larg_original_srcset !== 'undefined' && activate_larg_original_srcset !== false ) {
				active_larg_img.attr("srcset", activate_larg_original_srcset );
				active_larg_img.removeAttr("data-osrcset");
			}

			active_larg_img.imagesLoaded(function() {
				jQuery('.a3-portfolio-item-image-container').find('.a3-portfolio-loading').fadeOut();
			});
		});

		jQuery('.a3-portfolio-single-wrap').find("div.a3-portfolio-gallery-thumb-lazy").each(function(){
			if ( typeof jQuery(this).attr('data-bg') !== 'undefined' ) {
				jQuery(this).css('background-image', 'url('+jQuery(this).attr('data-bg')+')')
				jQuery(this).removeClass('a3-portfolio-gallery-thumb-lazy');
			}
		});
	});

	jQuery(window).on('resize',function()  {
		if(jQuery(".a3-portfolio-single-wrap").width() <= 767 ){
			jQuery(".a3-portfolio-single-wrap").addClass("a3-portfolio-expander-popup-mobile");
		}else{
			jQuery(".a3-portfolio-single-wrap").removeClass("a3-portfolio-expander-popup-mobile");
		}
	});

	jQuery(document).on("click",".pg_grid_content",function(){
		var current_thumb_click = jQuery(this);
		var item_id = jQuery(this).attr('item_id');
		var active_larg_img_container = jQuery(this).parents('.a3-portfolio-single-wrap').find( '.a3-portfolio-item-image-container');

		active_larg_img_container.find('img').attr("srcset",'');
		active_larg_img_container.find('.a3-portfolio-loading').fadeIn( 400,function () {
			active_larg_img_container.find('img').attr('src', current_thumb_click.attr('data-originalfull')).attr('item_id', item_id );

			var activate_larg_original_srcset = current_thumb_click.attr('data-osrcset');
		 	if ( typeof activate_larg_original_srcset !== 'undefined' && activate_larg_original_srcset !== false ) {
				active_larg_img_container.find('img').attr("srcset", activate_larg_original_srcset );
			}

			active_larg_img_container.find('img').imagesLoaded(function() {
				active_larg_img_container.find('.a3-portfolio-loading').fadeOut();
			});
		});

		jQuery(this).parent(".pg_grid").siblings(".pg_grid").removeClass('current_img');
		jQuery(this).parent(".pg_grid ").addClass('current_img');
		var caption_text = '';
		if( jQuery(this).attr('data-caption') != '' ){
			caption_text = '<div class="portfolio_caption_text">'+jQuery(this).attr('data-caption')+'</div>';
		}
		active_larg_img_container.find('.caption_text_container').html(caption_text);
	});
});
