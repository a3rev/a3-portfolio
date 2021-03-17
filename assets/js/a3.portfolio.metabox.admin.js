jQuery( function( $ ){

	// Show & Hide Custom Meta Link
	$(document).on( 'change', '.a3-portfolio-meta-tag-link', function(){
		if ( $(this).val() != '0' ) {
			$(this).parents('tr').siblings('tr').find('.portfolio_meta_link_url_row').slideUp();
		} else {
			$(this).parents('tr').siblings('tr').find('.portfolio_meta_link_url_row').slideDown();
		}
	});

	// Product gallery file uploads
	var portfolio_gallery_frame;
	var $image_gallery_ids = $('#portfolio_image_gallery');
	var $portfolio_images = $('#portfolio_images_container ul.portfolio_images');

	$('.add_portfolio_images').on( 'click', 'a', function( event ) {
		var $el = $(this);
		var attachment_ids = $image_gallery_ids.val();

		event.preventDefault();

		// If the media frame already exists, reopen it.
		if ( portfolio_gallery_frame ) {
			portfolio_gallery_frame.open();
			return;
		}

		// Create the media frame.
		portfolio_gallery_frame = wp.media.frames.portfolio_gallery = wp.media({
			// Set the title of the modal.
			title: $el.data('choose'),
			button: {
				text: $el.data('update'),
			},
			states : [
				new wp.media.controller.Library({
					title: $el.data('choose'),
					filterable :	'all',
					multiple: true,
				})
			]
		});

		// When an image is selected, run a callback.
		portfolio_gallery_frame.on( 'select', function() {

			var selection = portfolio_gallery_frame.state().get('selection');

			selection.map( function( attachment ) {

				attachment = attachment.toJSON();

				if ( attachment.id ) {
				attachment_ids = attachment_ids ? attachment_ids + "," + attachment.id : attachment.id;

				$portfolio_images.append('\
					<li class="image" data-attachment_id="' + attachment.id + '">\
						<img src="' + attachment.url + '" />\
						<ul class="actions">\
							<li><a href="#" class="delete" title="' + $el.data('delete') + '">' + $el.data('text') + '</a></li>\
						</ul>\
					</li>');
				}

			});

			$image_gallery_ids.val( attachment_ids );
		});

		// Finally, open the modal.
		portfolio_gallery_frame.open();
	});

	// Image ordering
	$portfolio_images.sortable({
		items: 'li.image',
		cursor: 'move',
		scrollSensitivity:40,
		forcePlaceholderSize: true,
		forceHelperSize: false,
		helper: 'clone',
		opacity: 0.65,
		placeholder: 'metabox-sortable-placeholder',
		start:function(event,ui){
			ui.item.css('background-color','#f6f6f6');
		},
		stop:function(event,ui){
			ui.item.removeAttr('style');
		},
		update: function(event, ui) {
			var attachment_ids = '';

			$('#portfolio_images_container ul li.image').css('cursor','default').each(function() {
				var attachment_id = $(this).attr( 'data-attachment_id' );
				attachment_ids = attachment_ids + attachment_id + ',';
			});

			$image_gallery_ids.val( attachment_ids );
		}
	});

	// Remove images
	$('#portfolio_images_container').on( 'click', 'a.delete', function() {
		$(this).closest('li.image').remove();

		var attachment_ids = '';

		$('#portfolio_images_container ul li.image').css('cursor','default').each(function() {
			var attachment_id = $(this).attr( 'data-attachment_id' );
			attachment_ids = attachment_ids + attachment_id + ',';
		});

		$image_gallery_ids.val( attachment_ids );

		//runTipTip();

		return false;
	});

	// Show & Hide the Single Layout Container 
	$(document).ready(function() {

		if ( $("input.a3_portfolio_meta_layout_column:checked").val() == '1') {
			$(".portfolio_single_2_column_container").hide();
		} else {
			$(".portfolio_single_2_column_container").show();
		}

		$(document).on( "a3rev-ui-onoff_checkbox-switch", '.a3_portfolio_meta_layout_column', function( event, value, status ) {
			if ( status == 'true' ) {
				$(".portfolio_single_2_column_container").hide();
			} else {
				$(".portfolio_single_2_column_container").show();
			}
		});
	});

	// ATTRIBUTE TABLES

	$( document.body )
		.on( 'a3-portfolio-enhanced-select-init', function() {

			// Regular select boxes
			$( ':input.a3-portfolio-enhanced-select, :input.chosen_select' ).filter( ':not(.enhanced)' ).each( function() {
				$( this ).addClass( 'enhanced' ).chosen();
			});
		});

	// Initial order
	var portfolio_attribute_items = $('.portfolio_attributes').find('.portfolio_attribute').get();

	portfolio_attribute_items.sort(function(a, b) {
	   var compA = parseInt( $( a ).attr( 'rel' ), 10 );
	   var compB = parseInt( $( b ).attr( 'rel' ), 10 );
	   return (compA < compB) ? -1 : (compA > compB) ? 1 : 0;
	});
	$( portfolio_attribute_items ).each( function( idx, itm ) {
		$( '.portfolio_attributes' ).append(itm);
	});

	$( '.portfolio_attributes .portfolio_attribute' ).each( function( index, el ) {
		if ( $( el ).css( 'display' ) !== 'none' && $( el ).is( '.taxonomy' ) ) {
			$( 'select.attribute_taxonomy' ).find( 'option[value="' + $( el ).data( 'attribute-id' ) + '"]' ).prop( 'disabled', true );
		}
	});

	// Add rows
	$( 'button.add_attribute' ).on( 'click', function() {
		var attribute_id = $( 'select.attribute_taxonomy' ).val();
		if ( '' == attribute_id ) {
			window.alert( a3_portfolio_admin_meta_boxes.select_attribute_message );
			return false;
		}

		var $wrapper     = $( this ).closest( '#portfolio_attributes_panel' );
		var $attributes  = $wrapper.find( '.portfolio_attributes' );
		var data         = {
			action:   'a3_portfolio_add_attribute',
			attribute_id: parseInt( attribute_id ),
			security: a3_portfolio_admin_meta_boxes.add_attribute_nonce
		};

		$wrapper.block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});

		$.post( a3_portfolio_admin_meta_boxes.ajax_url, data, function( response ) {
			$attributes.append( response );

			$( document.body ).trigger( 'a3-portfolio-enhanced-select-init' );
			$wrapper.unblock();

			$( document.body ).trigger( 'a3_portfolio_added_attribute' );
		});

		if ( attribute_id ) {
			$( 'select.attribute_taxonomy' ).find( 'option[value="' + attribute_id + '"]' ).prop( 'disabled', true );
			$( 'select.attribute_taxonomy' ).val( '' );
		}

		return false;
	});

	$( '.portfolio_attributes' ).on( 'click', 'button.select_all_attributes', function() {
		$( this ).closest( 'td' ).find( 'select option' ).prop( 'selected', true );
		$( this ).closest( 'td' ).find( 'select' ).trigger("chosen:updated");
		return false;
	});

	$( '.portfolio_attributes' ).on( 'click', 'button.select_no_attributes', function() {
		$( this ).closest( 'td' ).find( 'select option' ).prop( 'selected', false );
		$( this ).closest( 'td' ).find( 'select').trigger("chosen:updated");
		return false;
	});

	$( '.portfolio_attributes' ).on( 'click', '.remove_row', function() {
		if ( window.confirm( a3_portfolio_admin_meta_boxes.remove_attribute ) ) {
			var $parent = $( this ).parent().parent();

			$parent.remove();
			$( 'select.attribute_taxonomy' ).find( 'option[value="' + $parent.data( 'attribute-id' ) + '"]' ).prop( 'disabled', false );
		}
		return false;
	});

	// Attribute ordering
	$( '.portfolio_attributes' ).sortable({
		items: '.portfolio_attribute',
		cursor: 'move',
		axis: 'y',
		handle: 'h3',
		scrollSensitivity: 40,
		forcePlaceholderSize: true,
		helper: 'clone',
		opacity: 0.65,
		placeholder: 'a3-metabox-item-sortable-placeholder',
		start: function( event, ui ) {
			ui.item.css( 'background-color', '#f6f6f6' );
		},
		stop: function( event, ui ) {
			ui.item.removeAttr( 'style' );
		}
	});

	// Add a new attribute (via ajax)
	$( '.portfolio_attributes' ).on( 'click', 'button.add_new_attribute', function() {

		$( '.portfolio_attributes' ).block({ message: null, overlayCSS: { background: '#fff', opacity: 0.6 } });

		var $wrapper           = $( this ).closest( '.portfolio_attribute' );
		var attribute_id       = $wrapper.data( 'attribute-id' );
		var new_attribute_name = window.prompt( a3_portfolio_admin_meta_boxes.new_attribute_prompt );

		if ( new_attribute_name ) {

			var data = {
				action:   'a3_portfolio_add_new_attribute',
				attribute_id: attribute_id,
				term:     new_attribute_name,
				security: a3_portfolio_admin_meta_boxes.add_attribute_nonce
			};

			$.post( a3_portfolio_admin_meta_boxes.ajax_url, data, function( response ) {

				if ( response.error ) {
					// Error
					window.alert( response.error );
				} else if ( response.slug ) {
					// Success
					$wrapper.find( 'select.attribute_values' ).append( '<option value="' + response.slug + '" selected="selected">' + response.name + '</option>' );
					$wrapper.find( 'select.attribute_values' ).trigger("chosen:updated");
				}

				$( '.portfolio_attributes' ).unblock();
			});

		} else {
			$( '.portfolio_attributes' ).unblock();
		}

		return false;
	});

	// Save attributes
	$( '.save_attributes' ).on( 'click', function() {

		$( '#a3_portfolio_data_meta_box' ).block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});

		var data = {
			post_id:  a3_portfolio_admin_meta_boxes.post_id,
			data:     $( '.portfolio_attributes' ).find( 'input, select, textarea' ).serialize(),
			action:   'a3_portfolio_save_attributes',
			security: a3_portfolio_admin_meta_boxes.save_attributes_nonce
		};

		$.post( a3_portfolio_admin_meta_boxes.ajax_url, data, function() {
			$( '#a3_portfolio_data_meta_box' ).unblock();
			var this_page = window.location.toString();
			this_page = this_page.replace( 'post-new.php?', 'post.php?post=' + a3_portfolio_admin_meta_boxes.post_id + '&action=edit&' );
		});
	});
});
