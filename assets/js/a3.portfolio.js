///////////////////////////
// GET THE URL PARAMETER //
///////////////////////////
function a3_portfolio_getUrlVars(hashdivider) {
	try { var vars = [], hash;
		var hashes = window.location.href.slice(window.location.href.indexOf(hashdivider) + 1).split('_');
		for(var i = 0; i < hashes.length; i++) {
			hashes[i] = hashes[i].replace('%3D',"=");
			hash = hashes[i].split('=');
			vars.push(hash[0]);
			vars[hash[0]] = hash[1];
		}
		return vars;
	} catch(e) {
	}
}

//////////////////////////
// SET THE URL PARAMETER //
///////////////////////////
function a3_portfolio_updateURLParameter(paramVal){
	var yScroll=document.body.scrollTop;
	if ( paramVal == '' && window.location.href.indexOf("#") < 0 ) {
		return false;
	}

	var baseurl = window.location.pathname.split("#")[0];
    var url = baseurl.split("#")[0];
    if ( typeof paramVal === 'undefined' ) paramVal="";
	if (paramVal.length==0)
	    par="#";
   	else
		par='#'+paramVal;
	if(history.pushState) {
		history.pushState(null, null, par);
	} else {
		location.hash = par;
	}
}

// CHECK IPHONE Return boolean TRUE/FALSE
function a3_portfolio_isiPhone(){
	return ( (navigator.platform.indexOf("iPhone") != -1) || (navigator.platform.indexOf("iPod") != -1) || (navigator.platform.indexOf("iPad") != -1) );
}

function a3_portfolio_is_iOS_8() {
		if ( ~navigator.userAgent.indexOf('OS 8_') ){
		return true;
		}
		return false;
}

function a3_portfolio_detectMobile() {
	if( navigator.userAgent.match(/Android/i)
	 || navigator.userAgent.match(/webOS/i)
	 || navigator.userAgent.match(/iPhone/i)
	 || navigator.userAgent.match(/iPad/i)
	 || navigator.userAgent.match(/iPod/i)
	 || navigator.userAgent.match(/BlackBerry/i)
	 || navigator.userAgent.match(/Windows Phone/i)
	){
	    return true;
	}
	else {
	   return false;
	}
}

function a3_portfolio_centerpdv() {
	try {
		var pdv = jQuery('body').find('.a3-portfolio-expander-popup');
		var pleft=jQuery('body').width()/2 - pdv.width()/2;
		pdv.css({'left':pleft+"px"});
	} catch(e) {
	}
}

// REMOVE ACTIVE THUMB EFFECTS
function a3_portfolio_removeActiveThumbs() {
	jQuery('.a3-portfolio-box-content').find('.a3-portfolio-item').each(function() {
		jQuery(this).removeClass('active');
		if (!jQuery(this).hasClass('latest-active')) jQuery(this).find('div.a3-portfolio-card-overlay').fadeOut(200);
	});
}

// CLOSE DETAILVIEW
function a3_portfolio_closeDetailView(portfolio_boxes, speed) {
	var ie = false;
	var ie9 = (document.documentMode == 9);

	jQuery('body').find('.a3-portfolio-activate-up-arrow').remove();
	jQuery('.a3-portfolio-container').removeClass('a3-portfolio-fixed-scroll');
	var pdv = jQuery('body').find('.a3-portfolio-expander-popup');
	setTimeout(function() {
		if (pdv.length) {
			a3_portfolio_removeActiveThumbs();
			clearInterval(pdv.data('interval'));
			pdv.animate({'height':'0px','opacity':'0'},{duration:speed, complete:function() { jQuery(this).remove();}});
			if ( a3_portfolio_script_params.have_filters_script ) {
				a3_portfolio_moveThumbs(pdv.data('itemstomove'),0, speed);
			}
		}
		setTimeout(function() {
			var number = 0;
			jQuery('.a3-portfolio-container').each(function(){
				number++;
				var portfolio_boxes = jQuery(this).find('.a3-portfolio-box-content');
				portfolio_boxes.data('height',portfolio_boxes.height());
			});
			setTimeout(function() {
				//portfolio_boxes.data('height',portfolio_boxes.height());
			},speed);  //500 old value
		},speed);
		if (!ie && !ie9) a3_portfolio_updateURLParameter("");
	},150)
}

function add_arrow_active_thumb(){
	//var pdv = jQuery('body').find('.a3-portfolio-expander-popup');
	var thumb = jQuery('body').find('.a3-portfolio-item.active');
	jQuery('body').find('.a3-portfolio-activate-up-arrow').remove();
	jQuery('body').append('<div class="a3-portfolio-activate-up-arrow" style="opacity: 0;"></div>');
	var arrow = jQuery('.a3-portfolio-activate-up-arrow');
	var thumb_pos_left = thumb.offset().left+((thumb.width()/2)-10);
	/*if(pdv.length){
		thumb_pos_top = pdv.offset().top-10;
	}else{
		thumb_pos_top = thumb.offset().top+thumb.height()+parseInt(thumb.css('marginBottom'),0)-10;
	}*/
	thumb_pos_top = thumb.offset().top+thumb.height()+parseInt(thumb.css('marginBottom'),0)-12;
	arrow.css({top:thumb_pos_top,left:thumb_pos_left,display:'block',opacity:1});
}

// MOVE THE THUMBS
function a3_portfolio_moveThumbs(itemstomove,offset, speed) {
	var ie = false;
	var ie9 = (document.documentMode == 9);

	jQuery.each(itemstomove,function() {
		var thumb = jQuery(this);
		thumb.stop(true);
		thumb.animate({'top':(thumb.data('top')+offset)+"px"},{duration:speed,queue:false});
	});
	var number = 0;
	jQuery('.a3-portfolio-container').each(function(){
		number++;
		var portfolio_boxes = jQuery(this).find('.a3-portfolio-box-content');
		var portfolio_boxes_height = portfolio_boxes.data('height');
		if ( portfolio_boxes.find('.a3-portfolio-item.active').length > 0 ) {
			portfolio_boxes_height += offset;
		}
		if (ie || ie9) {
			portfolio_boxes.stop(true);
			portfolio_boxes.animate({'height':(portfolio_boxes_height)+"px"}, {duration:speed,queue:false});
		} else {
			portfolio_boxes.css({'height':Math.round(portfolio_boxes_height)+"px"});
		}
	});
}


jQuery(document).ready(function () {

	var $win = jQuery(window);
	var number_columns = a3_portfolio_script_params.number_columns;
	var card_image_height_fixed = a3_portfolio_script_params.card_image_height_fixed;
	var desktop_expander_top_alignment = a3_portfolio_script_params.desktop_expander_top_alignment;
	var mobile_expander_top_alignment = a3_portfolio_script_params.mobile_expander_top_alignment;
	var screen_width = jQuery('html').width();
	var relayout;
	var load_deeplink = false;
	var intervalOpenExpander = null;

	//Wordepress Adminbar
	var wpadminbar_height = 0;

	//Header Extender
	var headersticky_height = 0;

	var speed = 600;
	var scrollspeed = 600;
	var force_scrolltotop = true;
	var deeplink = a3_portfolio_getUrlVars("#");
	var ie = false;
	var ie9 = (document.documentMode == 9);
	var isRTL = false;
	if ( a3_portfolio_script_params.rtl == 1 ) {
		isRTL = true;
	}

	jQuery('.a3-portfolio-menus-container').find('ul.filter li').each(function() {
		var filter_class = jQuery(this).children('a').attr('data-filter');
		var portfolio_container = jQuery(this).parents('.a3-portfolio-container');
		if ( filter_class == '.a3-portfolio-item' || portfolio_container.find('.a3-portfolio-item' + filter_class).length > 0 ) {
			jQuery(this).show();
		}
	});

	if ( ! a3_portfolio_script_params.have_filters_script ) {
		jQuery('.a3-portfolio-container').each(function() {
			var portfolio_container = jQuery(this);
			var portfolio_boxes = jQuery(this).find('.a3-portfolio-box-content');
			var custom_columns = jQuery(this).data('column');
			if ( typeof custom_columns !== 'undefined' ) {
				number_columns = custom_columns;
			}

			if ( screen_width <= 767 && screen_width >= 379 && number_columns > 2 ) {
				number_columns = 2;
			}

			// Get wide of image container if Image Height is fixed to set % for height based wide of image container
			if ( card_image_height_fixed != false ) {
				var image_container_width = portfolio_boxes.find('.a3-portfolio-item').first().find('.a3-portfolio-item-block').innerWidth();

				// Set Fixed Height for image container based wide of image container
				if ( image_container_width > 50 ) {
					portfolio_boxes.find('.a3-portfolio-item-block').find('.a3-portfolio-card-image-container').height( ( parseInt(card_image_height_fixed) * image_container_width ) / 100 );
				}
			}

			// Custom set sizes value for all image on card support Responsi Image
			var card_image_max_width = portfolio_container.innerWidth() / number_columns;
			// If card image need to have width > 300px then auto change
			if ( card_image_max_width >= 300 ) {
				portfolio_boxes.find('.a3-portfolio-item-block img').each(function() {
					var image_sizes = jQuery(this).attr('sizes');
					if ( typeof image_sizes !== 'undefined' ) {
						jQuery(this).attr('sizes', '(max-width: ' + card_image_max_width + 'px) 100vw, ' + card_image_max_width + 'px' )
					}
				});
			}

			var selector = portfolio_container.find('.a3-portfolio-menus-container li a.active').attr('data-filter');
			if ( typeof selector == 'undefined') selector = '.a3-portfolio-item';
			portfolio_boxes.find('.a3-portfolio-item').removeClass('a3-portfolio-item-first a3-portfolio-item-last');

			all_items_selector = portfolio_boxes.find(selector);
			all_items_selector.each( function(index, value ){
				if( index == 0 ) {
					jQuery(this).addClass('a3-portfolio-item-first');
				}
				if( index == all_items_selector.length-1 ){
					jQuery(this).addClass('a3-portfolio-item-last');
				}
			});

			portfolio_boxes.on( 'layoutComplete', function( msrInstance, laidOutItems ){
				console.log('[a3 Portfolio] - Start load the layout');

				var selector = portfolio_container.find('.a3-portfolio-menus-container li a.active').attr('data-filter');
				if ( typeof selector == 'undefined') selector = '.a3-portfolio-item';
				portfolio_boxes.find('.a3-portfolio-item').removeClass('a3-portfolio-item-first a3-portfolio-item-last');

				all_items_selector = portfolio_boxes.find(selector);
				all_items_selector.each( function(index, value ){
					if( index == 0 ) {
						jQuery(this).addClass('a3-portfolio-item-first');
					}
					if( index == all_items_selector.length-1 ){
						jQuery(this).addClass('a3-portfolio-item-last');
					}
				});

				if (deeplink[0].split('item-').length>1) {
					if ( load_deeplink == false ) {
						load_deeplink = true;
						var current_item = parseInt(deeplink[0].split('item-')[1],0);
						var active_larg_img = null;
						var card_overlay = null;
						var view_more_bt = null;

						jQuery('body').find('.a3-portfolio-item').each( function( index, value ){
							if ( index == current_item ) {
								active_larg_img = jQuery(this).find('.active.item img');
								card_overlay = jQuery(this).find('.a3-portfolio-card-image-container .a3-portfolio-card-opens-expander');
								view_more_bt = jQuery(this).find('.a3-portfolio-card-viewmore .a3-portfolio-card-opens-expander');
							}
						});

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
						active_larg_img.removeClass("a3-portfolio-large-lazy");
						active_larg_img.imagesLoaded(function() {
							//console.log('Deep Link Image is loaded');
							intervalOpenExpander = setInterval( function() {
								clearInterval(intervalOpenExpander);
								if ( card_overlay.length > 0 ) {
									card_overlay.trigger("click");
								} else if ( view_more_bt.length > 0 ) {
									view_more_bt.trigger("click");
								}
							}, 3000 );

					    });
					}
				}
			});

			portfolio_boxes.masonry({
				isRTL: isRTL,
				itemSelector: '.a3-portfolio-item',
				columnWidth: portfolio_boxes.parent().width()/number_columns,
				gutterWidth: (portfolio_boxes.width()-portfolio_boxes.parent().width())/number_columns,
				transitionDuration: 0
			});

		});
	}

	// Load all large images after 15 seconds page is loaded
	$win.on("load", function() {
		setTimeout( function() {
			console.log('[a3 Portfolio] - Start Load First Large Images inside the exapander of all Portfolio Items');
			jQuery('.a3-portfolio-container').each(function() {
				jQuery(this).find("img.a3-portfolio-large-lazy").each(function(){
					var activate_larg_original = jQuery(this).attr('data-original');
				 	if ( typeof activate_larg_original !== 'undefined' && activate_larg_original !== false ) {
						jQuery(this).attr("src", activate_larg_original );
						jQuery(this).removeAttr("data-original");
					}
					var activate_larg_original_srcset = jQuery(this).attr('data-osrcset');
				 	if ( typeof activate_larg_original_srcset !== 'undefined' && activate_larg_original_srcset !== false ) {
						jQuery(this).attr("srcset", activate_larg_original_srcset );
						jQuery(this).removeAttr("data-osrcset");
					}
					jQuery(this).removeClass("a3-portfolio-large-lazy");
				});
			});
		}, 15000 );
	});

	jQuery(document).on( 'click', '.pg_grid_content',function(){
		var current_thumb_click = jQuery(this);
		var current_card_activated = jQuery('body').find('.a3-portfolio-item.active');
		var portfolio_boxes = current_card_activated.parents('.a3-portfolio-box-content');

		var active_larg_img_container = jQuery(this).parents('.a3-portfolio-inner-wrap').find( '.a3-portfolio-item-image-container');

		var activate_larg_original_srcset = current_thumb_click.attr('data-osrcset');

		active_larg_img_container.find('img').attr("srcset",'');
	 	if ( typeof activate_larg_original_srcset !== 'undefined' && activate_larg_original_srcset !== false ) {
			active_larg_img_container.find('img').attr("srcset", activate_larg_original_srcset );
		}
		active_larg_img_container.find('img').attr('src',current_thumb_click.attr('data-originalfull'));

		active_larg_img_container.find('.a3-portfolio-loading').fadeIn( 100,function () {

			active_larg_img_container.find('img').imagesLoaded(function() {
				active_larg_img_container.find('.a3-portfolio-loading').fadeOut( 100 );

				current_thumb_click.parent(".pg_grid").siblings(".pg_grid").removeClass('current_img');
				current_thumb_click.parent(".pg_grid ").addClass('current_img');
				var caption_text = '';
				if( current_thumb_click.attr('data-caption') != '' ){
					caption_text = '<div class="portfolio_caption_text">'+current_thumb_click.attr('data-caption')+'</div>';
				}
				active_larg_img_container.find('.caption_text_container').html(caption_text);

				var pdv = jQuery('body').find('.a3-portfolio-expander-popup');
				var pdcc = pdv.find('.a3-portfolio-inner-wrap');
				var pdvpad = parseInt(pdcc.css('paddingBottom'),0) + parseInt(pdcc.css('paddingTop'),0);
				var offset = pdcc.height()+pdvpad + parseInt(pdv.css('marginBottom'),0);
				pdv.height(jQuery(".a3-portfolio-inner-wrap").outerHeight());
				if (pdv.height.length) {
					if ( a3_portfolio_script_params.have_filters_script ) {
						a3_portfolio_moveThumbs(pdv.data('itemstomove'),offset, speed);
					}
				}
				if(pdcc.width() <= 586 ){
					var pdvScrollTop = current_card_activated.offset().top + current_card_activated.outerHeight() - mobile_expander_top_alignment;
					jQuery('body,html').animate({
						scrollTop: pdvScrollTop
					}, {
						duration: speed,
						queue: false,
					});
				}
			});
		});
	});

	//Click Filter
	jQuery(document).on("click tap", ".a3-portfolio-navigation-mobile .a3-portfolio-icon-close", function(){
		var portfolio_container = jQuery(this).parents('.a3-portfolio-container');
		portfolio_container.find( ".a3-portfolio-menus-container" ).slideUp( "fast", function() {
			portfolio_container.find( '.a3-portfolio-navigation-mobile-icon' ).removeClass('a3-portfolio-icon-close').addClass('a3-portfolio-icon-list').html('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M48 128c-17.7 0-32 14.3-32 32s14.3 32 32 32H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H48zm0 192c-17.7 0-32 14.3-32 32s14.3 32 32 32H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H48z"/></svg>');
		});
	});
	jQuery(document).on("click tap", ".a3-portfolio-navigation-mobile .a3-portfolio-icon-list", function(){
		var portfolio_container = jQuery(this).parents('.a3-portfolio-container');
		portfolio_container.find( ".a3-portfolio-menus-container" ).slideDown( "fast", function() {
			portfolio_container.find( '.a3-portfolio-navigation-mobile-icon' ).removeClass('a3-portfolio-icon-list').addClass('a3-portfolio-icon-close').html('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"/></svg>');
		});
	});

	// Combine multile Attribute Filter + one Category Filter when click on Category Filter
	function a3_portfolio_category_filter_click( portfolio_boxes, selector ) {
		var attribute_term_classes = [];
		jQuery('ul.attribute-filter').each(function(){
			jQuery(this).find('li.active').each(function() {
				filter_class = jQuery(this).children('a').attr('data-filter');
				if ( '' != filter_class ) {
					attribute_term_classes.push( filter_class );
				}
			});
		});

		if ( typeof selector != 'undefined' && '' != selector ) {
			attribute_term_classes.push( selector );
		}

		attribute_term_classes_text = '';
		jQuery.each( attribute_term_classes, function( index, term_class ){
			portfolio_boxes.find('.a3-portfolio-item').not(term_class).hide();
			attribute_term_classes_text += term_class;
		});
		portfolio_boxes.find('.a3-portfolio-item'+attribute_term_classes_text).show();
		portfolio_boxes.masonry();
	}

	// Combine multile Attribute Filter + multile Category Filter when click on Attrbite Filter widget
	function a3_portfolio_attribute_filter_click() {
		var attribute_term_classes = [];
		jQuery('ul.attribute-filter').each(function(){
			jQuery(this).find('li.active').each(function() {
				filter_class = jQuery(this).children('a').attr('data-filter');
				if ( '' != filter_class ) {
					attribute_term_classes.push( filter_class );
				}
			});
		});

		jQuery('.a3-portfolio-container').each(function(){
			portfolio_boxes = jQuery(this).find('.a3-portfolio-box-content');

			filter_class = jQuery(this).find('.a3-portfolio-menus-container li a.active').attr('data-filter');
			if ( typeof filter_class != 'undefined' && '' != filter_class ) {
				attribute_term_classes.push( filter_class );
			}

			attribute_term_classes_text = '';
			jQuery.each( attribute_term_classes, function( index, term_class ){
				portfolio_boxes.find('.a3-portfolio-item').not(term_class).hide();
				attribute_term_classes_text += term_class;
			});
			portfolio_boxes.find('.a3-portfolio-item'+attribute_term_classes_text).show();
			portfolio_boxes.masonry();
		});
	}

	// Category Filter
	jQuery(document).on("click", ".a3-portfolio-menus-container li a", function(){
		var portfolio_container = jQuery(this).parents('.a3-portfolio-container');
		var portfolio_boxes = portfolio_container.find('.a3-portfolio-box-content');
		var active_item = jQuery('.a3-portfolio-container').find('.a3-portfolio-item.active');

		if( active_item.length > 0 ) {
			a3_portfolio_updateURLParameter("");

			jQuery('body').find('.a3-portfolio-activate-up-arrow').remove();
			var pdv = jQuery('body').find('.a3-portfolio-expander-popup');
			setTimeout(function() {
				if (pdv.length) {
					a3_portfolio_removeActiveThumbs();
					clearInterval(pdv.data('interval'));
					pdv.animate({'height':'0px','opacity':'0'},{duration:speed, complete:function() { jQuery(this).remove();}});
					if ( a3_portfolio_script_params.have_filters_script ) {
						a3_portfolio_moveThumbs(pdv.data('itemstomove'),0, speed);
					}
				}
				setTimeout(function() {
					portfolio_boxes.data('height',portfolio_boxes.height());
				},speed);
				if (!ie && !ie9) a3_portfolio_updateURLParameter("");
			},150);
		}

		portfolio_container.find('.a3-portfolio-menus-container li a').removeClass('active');
		jQuery(this).addClass('active');

		if ( ! a3_portfolio_script_params.have_filters_script ) {
			var selector = jQuery(this).attr('data-filter');
			a3_portfolio_category_filter_click( portfolio_boxes, selector )
		}
		return false;
	});

	// Attribute Filter
	jQuery(document).on("click", "ul.attribute-filter li a", function(){
		var attribute_filter_container = jQuery(this).parents('ul.attribute-filter');
		var attribute_filter_item_click = jQuery(this).parent('li');

		if ( attribute_filter_item_click.hasClass('remove-filter') ) {
			attribute_filter_container.find('li').removeClass('active');
			attribute_filter_item_click.slideUp();
		} else if ( attribute_filter_item_click.hasClass('active') ) {
			attribute_filter_item_click.removeClass('active');
			attribute_filter_container.find('li.remove-filter').slideUp();
		} else {
			attribute_filter_container.find('li').removeClass('active');
			attribute_filter_item_click.addClass('active');
			attribute_filter_container.find('li.remove-filter').slideDown();
		}

		jQuery('.a3-portfolio-container').each(function(){
			var portfolio_container = jQuery(this);
			var portfolio_boxes = portfolio_container.find('.a3-portfolio-box-content');
			var active_item = portfolio_container.find('.a3-portfolio-item.active');

			if( active_item.length > 0 ) {
				a3_portfolio_updateURLParameter("");

				jQuery('body').find('.a3-portfolio-activate-up-arrow').remove();
				var pdv = jQuery('body').find('.a3-portfolio-expander-popup');
				setTimeout(function() {
					if (pdv.length) {
						a3_portfolio_removeActiveThumbs();
						clearInterval(pdv.data('interval'));
						pdv.animate({'height':'0px','opacity':'0'},{duration:speed, complete:function() { jQuery(this).remove();}});
						if ( a3_portfolio_script_params.have_filters_script ) {
							a3_portfolio_moveThumbs(pdv.data('itemstomove'),0, speed);
						}
					}
					setTimeout(function() {
						portfolio_boxes.data('height',portfolio_boxes.height());
					},speed);
					if (!ie && !ie9) a3_portfolio_updateURLParameter("");
				},150);
			}
		});

		if ( ! a3_portfolio_script_params.have_filters_script ) {
			a3_portfolio_attribute_filter_click();
		}

		return false;
	});

	//Items Event
	jQuery(document).on("mouseenter touchstart", ".a3-portfolio-item", function(){
		jQuery(this).find('.a3-portfolio-item-block').find('div.a3-portfolio-card-overlay').fadeIn(300);
	});
	jQuery(document).on("mouseleave", ".a3-portfolio-item", function(){
		if (!jQuery(this).hasClass("active")) jQuery(this).find('.a3-portfolio-item-block').find('div.a3-portfolio-card-overlay').fadeOut(200);
	});

	jQuery(document).on("click tap", ".a3-portfolio-card-opens-expander", function(){
		var thumb = jQuery(this).parents('.a3-portfolio-item');
		var portfolio_container = jQuery(this).parents('.a3-portfolio-container');
		var container_id = portfolio_container.data('container-id');
		var portfolio_boxes = portfolio_container.find('.a3-portfolio-box-content');
		// The CLicked Thumb
		jQuery('body').find('.a3-portfolio-activate-up-arrow').remove();

		// IF THE CLICKED THUMB IS ALREADY SELECTED, WE NEED TO CLOSE THE WINDOWS SIMPLE
		if (thumb.hasClass("active")) {
			a3_portfolio_moveToActivateRow(portfolio_boxes);
			thumb.removeClass("active");
			a3_portfolio_closeDetailView(portfolio_boxes, speed);
			// OTHER WAY WE CLOSE THE WINDOW (IF NECESsARY, OPEN AGAIN, AND DESELET / SELECT THE RIGHT THUMBS
		}  else {
			// load gallery thumb images for portfolio item when expand
			console.log('[a3 Portfolio] - Loading all thumb images inside expander of Portfolio Index - ' + thumb.data('index') );
			thumb.find("div.a3-portfolio-gallery-thumb-lazy").each(function(){
				if ( typeof jQuery(this).attr('data-bg') !== 'undefined' ) {
					jQuery(this).css('background-image', 'url('+jQuery(this).attr('data-bg')+')');
					jQuery(this).removeClass('a3-portfolio-gallery-thumb-lazy');
				}
			});

		 	a3_portfolio_updateURLParameter("item-"+thumb.index('.a3-portfolio-box-content .a3-portfolio-item'));
		 	thumb.addClass("latest-active");
		 	a3_portfolio_removeActiveThumbs();
		 	thumb.removeClass("latest-active");
		 	thumb.addClass("active");

		 	var active_larg_img = thumb.find('.a3-portfolio-item-expander-content .active.item img');
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
			active_larg_img.removeClass("a3-portfolio-large-lazy");

			//active_larg_img.imagesLoaded(function() {
				//console.log('Large Image is loaded');
			 	// CHECK IF WE ALREADY HAVE THE DETAIL WINDOW OPEN
				var pdv = jQuery('body').find('.a3-portfolio-expander-popup');
				if (pdv.length) {
					var fade=false;
					clearInterval(pdv.data('interval'));
					pdv.animate({'height':'0px','opacity':'0'},{duration:speed, complete:function() { jQuery(this).remove();}});
					var delay=speed+50;
					if ( a3_portfolio_script_params.have_filters_script ) {
						a3_portfolio_moveThumbs(pdv.data('itemstomove'),0, speed);
					}
					setTimeout(function() {
						var wpadminbar = jQuery('#wpadminbar');
						if( wpadminbar.length > 0) wpadminbar_height = wpadminbar.outerHeight();

						var headersticky = jQuery('.toolbar-ctn');
						if( headersticky.length > 0) headersticky_height = headersticky.outerHeight();

						var card_height = thumb.outerHeight();

						if ( jQuery('html').width() <= 600 ) {
							var pdvScrollTop = thumb.offset().top - headersticky_height + card_height - mobile_expander_top_alignment;
						} else {
							var pdvScrollTop = thumb.offset().top - wpadminbar_height - headersticky_height + card_height - desktop_expander_top_alignment;
						}
					 	jQuery('body,html').animate({
	                    	scrollTop: pdvScrollTop
						}, {
							duration: scrollspeed,
							queue: false
						});
						if (force_scrolltotop) {
							a3_portfolio_openDetailView(container_id, active_larg_img, portfolio_boxes,thumb,fade);
						} else {
							setTimeout(function () {
								a3_portfolio_openDetailView(container_id, active_larg_img, portfolio_boxes,thumb,fade);
							},scrollspeed)
						}

					},delay)
				} else {
					var wpadminbar = jQuery('#wpadminbar');
					if( wpadminbar.length > 0) wpadminbar_height = wpadminbar.outerHeight();

					var headersticky = jQuery('.toolbar-ctn');
					if( headersticky.length > 0) headersticky_height = headersticky.outerHeight();

					var card_height = thumb.outerHeight();

					if ( jQuery('html').width() <= 600 ) {
						var pdvScrollTop = thumb.offset().top - headersticky_height + card_height - mobile_expander_top_alignment;
					} else {
						var pdvScrollTop = thumb.offset().top - wpadminbar_height - headersticky_height + card_height - desktop_expander_top_alignment;
					}
					jQuery('body,html').animate({
						scrollTop: pdvScrollTop
					}, {
						duration: scrollspeed,
						queue: false
					});
					if (force_scrolltotop) {
						a3_portfolio_openDetailView(container_id, active_larg_img, portfolio_boxes,thumb);
					} else {
						setTimeout(function () {
							a3_portfolio_openDetailView(container_id, active_larg_img, portfolio_boxes,thumb);
						},scrollspeed)
					}
				}
			//});
		}
		return false;
	}); // END OF CLICK ON PORTFOLIO ITEM

	jQuery( window ).on( "orientationchange", function( event ) {
		jQuery('.a3-portfolio-container').each(function() {
			var portfolio_container = jQuery(this);
			var portfolio_boxes = portfolio_container.find('.a3-portfolio-box-content');
			var number_columns = a3_portfolio_script_params.number_columns;
			var screen_width = jQuery('html').width();

			var custom_columns = portfolio_container.data('column');
			if ( typeof custom_columns !== 'undefined' ) {
				number_columns = custom_columns;
			}
			if(screen_width <= 767 && screen_width >= 379 && number_columns > 2 ){
				number_columns = 2;
			}
			a3_portfolio_closeDetailView(portfolio_boxes, speed);
			a3_portfolio_centerpdv();

			if ( ! a3_portfolio_script_params.have_filters_script ) {
				setTimeout(function () {
					portfolio_boxes.masonry({
						isRTL: isRTL,
						itemSelector: '.a3-portfolio-item',
						columnWidth: portfolio_boxes.parent().width()/number_columns,
						gutterWidth: (portfolio_boxes.width()-portfolio_boxes.parent().width())/number_columns
					});
				},500);
			}
		});
	});

	jQuery(document).on( 'lazyload', '.a3-portfolio-thumb-lazy', function(e) {
		var portfolio_container = jQuery(this).parents('.a3-portfolio-container');
		var portfolio_boxes = portfolio_container.find('.a3-portfolio-box-content');
		portfolio_boxes.masonry();
	});

	// ON RESIZE REMOVE THE DETAIL VIEW CONTAINER
	jQuery('.a3-portfolio-container').each(function() {
		var portfolio_container = jQuery(this);
		var portfolio_boxes = portfolio_container.find('.a3-portfolio-box-content');
		var number_columns = a3_portfolio_script_params.number_columns;
		var screen_width = jQuery('html').width();

		var custom_columns = portfolio_container.data('column');
		if ( typeof custom_columns !== 'undefined' ) {
			number_columns = custom_columns;
		}
		if(screen_width <= 767 && screen_width >= 379 && number_columns > 2 ){
			number_columns = 2;
		}

		if (!ie) {
			jQuery(window).on('resize',function()  {
				if ( jQuery('html').width() > 767 ) {
					a3_portfolio_closeDetailView(portfolio_boxes, speed);
					a3_portfolio_centerpdv();
					if ( ! a3_portfolio_script_params.have_filters_script ) {
						portfolio_boxes.masonry({
							isRTL: isRTL,
							itemSelector: '.a3-portfolio-item',
							columnWidth: portfolio_boxes.parent().width()/number_columns,
							gutterWidth: (portfolio_boxes.width()-portfolio_boxes.parent().width())/number_columns
						});
					}
				}
			 });
		} else {
			if ( ! a3_portfolio_script_params.have_filters_script ) {
				portfolio_boxes.masonry({
					isRTL: isRTL,
					itemSelector: '.a3-portfolio-item',
					columnWidth: portfolio_boxes.parent().width()/number_columns,
					gutterWidth: (portfolio_boxes.width()-portfolio_boxes.parent().width())/number_columns
				});
			}
		}
	});

	// Resize Height of Card Image Container when wind is resized
	if ( card_image_height_fixed != false ) {
		jQuery(window).on('resize',function()  {
			jQuery('.a3-portfolio-container').each(function() {
				var portfolio_boxes = jQuery(this).find('.a3-portfolio-box-content');

				// Get wide of image container if Image Height is fixed to set % for height based wide of image container
				var image_container_width = portfolio_boxes.find('.a3-portfolio-item-first').find('.a3-portfolio-item-block').innerWidth();

				if ( image_container_width > 10 ) {
					// Set Fixed Height for image container based wide of image container
					portfolio_boxes.find('.a3-portfolio-item-block').find('.a3-portfolio-card-image-container').height( ( parseInt(card_image_height_fixed) * image_container_width ) / 100 );
				}
			});
		});
	}

	function a3_portfolio_moveToActivateRow( portfolio_boxes ) {
		var wpadminbar_height = 0;
		var wpadminbar = jQuery('#wpadminbar');
		if( wpadminbar.length > 0) wpadminbar_height = wpadminbar.outerHeight();
		var desktop_expander_top_alignment = a3_portfolio_script_params.desktop_expander_top_alignment;
		var mobile_expander_top_alignment = a3_portfolio_script_params.mobile_expander_top_alignment;

		var headersticky_height = 0;
		var headersticky = jQuery('.toolbar-ctn');
		if( headersticky.length > 0) headersticky_height = headersticky.outerHeight();

		var current_card_activated = portfolio_boxes.find('.a3-portfolio-item.active');

		if ( jQuery('html').width() <= 600 ) {
			var pdvScrollTop = current_card_activated.offset().top - headersticky_height - 20 - mobile_expander_top_alignment;
		} else {
			var pdvScrollTop = current_card_activated.offset().top - wpadminbar_height - headersticky_height - 20 - desktop_expander_top_alignment;
		}
	 	jQuery('body,html').animate({
        	scrollTop: pdvScrollTop
		}, {
			duration: scrollspeed,
			queue: false
		});
	}

	// OPEN THE DETAILVEW AND CATCH THE THUMBS BEHIND THE CURRENT THUMB
	function a3_portfolio_openDetailView(container_id, active_larg_img, portfolio_boxes,thumb) {
		jQuery('body').find('.a3-portfolio-activate-up-arrow').css({opacity:1});
		// The Top Position of the Current Item.
		currentTop= thumb.position().top;
		thumbOffsetTop= thumb.offset().top;
		// ALL ITEM WE NEED TO MOVE SOMEWHERE
		var itemstomove =[];
		portfolio_boxes.find('.a3-portfolio-item').each(function() {
			var curitem = jQuery(this);
			if (curitem.position().top>currentTop) itemstomove.push(curitem);
		});

		// Reset CurrentPositions
		jQuery.each(itemstomove,function() {
			var thumb = jQuery(this);
			thumb.data('oldPos',thumb.position().top);
		});

		// We Save the Height Of the current Container here.
		if ( typeof portfolio_boxes.data('height') !== 'undefined' ) {
			//if (portfolio_boxes.height()<portfolio_boxes.data('height')) 	portfolio_boxes.data('height',portfolio_boxes.height());
			portfolio_boxes.data('height',portfolio_boxes.height());
		} else {
			portfolio_boxes.data('height',portfolio_boxes.height());
		}

		// ADD THE NEW CONTENT IN THE DETAIL VIEW WINDOW.
		jQuery('body').append( a3_portfolio_script_params.expander_template ).find('.a3-portfolio-inner-wrap').append( thumb.children('.a3-portfolio-item-expander-content').html() );

		// CATCH THE DETAIL VIEW AND CONTENT CONTAINER
		var pdv = jQuery('body').find('.a3-portfolio-expander-popup');
		pdv.attr('container-id',container_id);
		pdv.css({maxWidth:(jQuery('body').innerWidth())});
		var closeb = pdv.find('.closebutton');
		var pdcc = pdv.find('.a3-portfolio-inner-wrap');
		var pdvpad = parseInt(pdcc.css('paddingBottom'),0) + parseInt(pdcc.css('paddingTop'),0);

		var pvctrl = 0;
		if(pdcc.width() <= 586 ){
			pdv.addClass("a3-portfolio-expander-popup-mobile");
			var pvctrl = 50;
		}else{
			pdv.removeClass("a3-portfolio-expander-popup-mobile");
			var pvctrl = 0;
		}

		closeb.on('click', function() {
			a3_portfolio_moveToActivateRow(portfolio_boxes);
			a3_portfolio_closeDetailView(portfolio_boxes, speed);
		});

		// ANIMATE THE OPENING OF THE CONTENT CONTAINER
		pdv.children('.a3-portfolio-loading').fadeIn();
		pdv.animate({'height':"300px"},{duration:speed,queue:false});
		// SAVE THE ITEMS TO MOVE IN THE PDV
		pdv.data('itemstomove',itemstomove);
		//PUT THE CONTAINER IN THE RIGHT POSITION
		pdv.css({'top':(thumbOffsetTop+thumb.height()+parseInt(thumb.css('marginBottom'),0)-2)+"px"});
		add_arrow_active_thumb();

		a3_portfolio_centerpdv();

		active_larg_img.imagesLoaded(function() {
			var offset = pdcc.height()+pvctrl+pdvpad + parseInt(pdv.css('marginBottom'),0);

			// FIRE THE CALLBACK HERE
			try{
				var callback = new Function(thumb.data('callback'));
				callback();
			} catch(e) {
			}

			jQuery.each(itemstomove,function() {
				var thumb = jQuery(this);
				thumb.data('top',parseInt(thumb.position().top,0));
			});

			pdv.animate({'height':Math.round(pdcc.height()+pdvpad)+"px"},{
				duration:speed,
				queue:false,
				complete:function(){
					pdv.children('.a3-portfolio-loading').fadeOut();
				}
			});

			// MOVE THE REST OF THE THUMBNAILS
			if ( a3_portfolio_script_params.have_filters_script ) { 
				a3_portfolio_moveThumbs(itemstomove,offset, speed);
			}

			var portfolioId = pdv.find('.a3-portfolio-item-image-container').attr('data-portfolioId');

			var data = {
				action: 'a3_portfolio_set_cookie',
				portfolio_id: portfolioId,
				lang: a3_portfolio_script_params.lang
			};
			jQuery.post( a3_portfolio_script_params.ajax_url, data, function(response) {
				if( response == true || response == 'true'){
					//console.log('Cookie saved');
				}else{
					//console.log('Cookie not save!');
				}
			});

			jQuery("body").trigger("a3_portfolio_open_expander");
		});
	}
});
