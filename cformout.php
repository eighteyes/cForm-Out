<?php
/*
Plugin Name: CFormout
Plugin URI: http://8isc.com
Description: Place shortcode [printForm id=] which corresponds with the id from cForms > Tracking to place data in post / page.
Version: 0.1a
Author: Sean Canton
Author URI: http://8isc.com
License: Fark
*/

function printForm( $atts ){
	global $wpdb;

	extract (shortcode_atts( array('id' => '1'), $atts ));

	$sql = "SELECT field_name,field_val FROM wp_cformsdata
	WHERE sub_id = $id";

	$data = $wpdb->get_results($sql, ARRAY_N);

	$html = "<style>.cf-out label {width: 100px;font-size: 12px; text-align:right; display: inline-block;padding-right:10px; } #cFormOut{float:left;}</style>";

	if ($data) {
		$i = 0;
		foreach ($data as $item) {
			// special handling
			if ($item[0] == 'page'){
				$title = trim($item[1], '/');
				$title = str_replace('-', ' ', $title);

				$html .= "<h1>".  ucwords($title) . "</h1>";
			} else {
				$html .= "<p class='cf-out'><label>$item[0]</label>";

				if (strpos($item[0], 'Upload') > 0) {
					$html .= "<img src='".plugins_url()."/cforms/uploads/". $id ."-". $item[1] ."'>";
				} else {
					$html .= "<span>$item[1]</span></p>";
				}
			}
		}
	}

	return "<div id='cFormOut'>" . $html . "</div>";
}

add_shortcode('printForm', 'printForm');
?>