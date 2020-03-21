<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wpdb;

$a3_portfolio_data = new \A3Rev\Portfolio\Data();
$a3_portfolio_data->install_database();

// Get all feature data
$all_features = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."a3_portfolio_feature " );
if ( is_array( $all_features ) && count( $all_features ) > 0 ) {
	foreach ( $all_features as $feature ) {
		$wpdb->query("INSERT INTO ".$wpdb->prefix."a3_portfolio_attributes( attribute_name, attribute_label, attribute_type ) VALUES( '".$feature->feature_slug."', '".$feature->feature_name."', 'text' )");
	}
}