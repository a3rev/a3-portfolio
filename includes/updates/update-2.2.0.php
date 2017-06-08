<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$a3_portfolio_global_item_expander_settings = get_option( 'a3_portfolio_global_item_expander_settings', array() );
$a3_portfolio_global_item_expander_settings['expander_thumb_gallery_position'] = 'right_gallery';

update_option( 'a3_portfolio_global_item_expander_settings', $a3_portfolio_global_item_expander_settings );