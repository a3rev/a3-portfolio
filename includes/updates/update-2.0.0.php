<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$a3_portfolio_global_settings = get_option( 'a3_portfolio_global_settings', array() );

update_option( 'a3_portfolio_global_item_expander_settings', $a3_portfolio_global_settings );