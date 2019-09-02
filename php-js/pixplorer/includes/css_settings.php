<?php

$filename_parts = explode('_', $file_name);

if( isset($h) && isset($w) ){

	if($w < 1280 && $h < 900){
		
		# Made for screens with less than 1280x768 resolutions
		$css['pv_left_width'] = 670; # Width of the image in the PV.
		$css['pv_height'] = 619; # Height of the PV.
		$css['comments_height_base'] = 167; # Comments height in the PV.
		
	}
	if(
		$w > 1279 && $h < 900 &&
		$w < 1360
	){
		# Made for screens higher than 1024x768 and less than 1360 x 768
		$css['pv_left_width'] = 930; 
		$css['pv_height'] = 619;
		$css['comments_height_base'] = 167;
	}
	if(
		$w > 1359 && $h < 1080 &&
		$w < 1440
	){
		# Made for screens higher than 1359 x 767 and less than 1440x900
		$css['pv_left_width'] = 990; 
		$css['pv_height'] = 619;
		$css['comments_height_base'] = 167;
	}
	
	if(
		$w > 1439 && $h < 1080 &&
		$w < 1600
	){
		# Made for screens higher than 1439x899 and less than 1600x900
		$css['pv_left_width'] = 1020; 
		$css['pv_height'] = 638;
		$css['comments_height_base'] = 186;
	}
	
	if(
		$w > 1599 && $h > 899 && $w < 1920
	){
		# Made for screens higher than 1599x899 and less than 1920x1080
		$css['pv_left_width'] = 1180; 
		$css['pv_height'] = 738;
		$css['comments_height_base'] = 286;
	}
	
	if(
		$w > 1919 && $h > 1079
	){
		# Made for screens higher than 1599x899 and less than 1920x1080
		$css['pv_left_width'] = 1500; 
		$css['pv_height'] = 938;
		$css['comments_height_base'] = 486;
	}
	
	$css['comments_height_big'] = $css['comments_height_base'] + 45;
	
}

// General rules.

?>