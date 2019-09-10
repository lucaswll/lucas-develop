<?php

if (!function_exists('isEdit')) {
	
	function isEdit() {
		
		$uri = request()->route()->uri();
		$ep = explode('/', $uri);
		return is_array($ep) ? end($ep) == "edit" : false;
		
	}
}

if (!function_exists('formatNumber')) {

	function formatNumber($value, $format = "en") {

		if ($format == "en") {
			$value = str_replace(",", ".", str_replace(".", "", $value));
		}

		return floatval($value);
	}
}

if (!function_exists('formatDate')) {
	
	function formatDate($dateString, $format = "d/m/y H:i") {
		return (new DateTime($dateString))->format($format);
	}
	
}