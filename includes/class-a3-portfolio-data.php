<?php

namespace A3Rev\Portfolio;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Data
{
	public function install_database() {
		global $wpdb;
		$collate = '';
		if ( $wpdb->has_cap( 'collation' ) ) {
			if( ! empty($wpdb->charset ) ) $collate .= "DEFAULT CHARACTER SET $wpdb->charset";
			if( ! empty($wpdb->collate ) ) $collate .= " COLLATE $wpdb->collate";
		}
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		$a3_portfolio_attributes = $wpdb->prefix . "a3_portfolio_attributes";
		if($wpdb->get_var("SHOW TABLES LIKE '$a3_portfolio_attributes'") != $a3_portfolio_attributes){
			$sql = "CREATE TABLE " . $a3_portfolio_attributes . " (
					   	  `attribute_id` bigint(20) NOT NULL auto_increment,
						  `attribute_name` varchar(250) NOT NULL,
						  `attribute_label` longtext Default NULL,
						  `attribute_type` varchar(250) NOT NULL Default 'select',
						  `attribute_orderby` varchar(250) NOT NULL Default 'menu_order',
						  PRIMARY KEY  (`attribute_id`)
						) $collate ;";
			$wpdb->query($sql);
		}

		$table_a3_portfolio_categorymeta = $wpdb->prefix. "a3_portfolio_categorymeta";

		if ($wpdb->get_var("SHOW TABLES LIKE '$table_a3_portfolio_categorymeta'") != $table_a3_portfolio_categorymeta) {
			$sql = "CREATE TABLE IF NOT EXISTS `{$table_a3_portfolio_categorymeta}` (
				  meta_id bigint(20) NOT NULL auto_increment,
				  a3_portfolio_category_id bigint(20) NOT NULL,
				  meta_key varchar(255) NULL,
				  meta_value longtext NULL,
				  PRIMARY KEY  (meta_id),
				  KEY a3_portfolio_category_id (a3_portfolio_category_id),
				  KEY meta_key (meta_key)
				) $collate; ";

			dbDelta($sql);
		}
	}
}
