<?php
###################################################################################################
if (!function_exists('include_jscript')) {
	#
	# Include JScript
	#
	# Inserts an HTML <script> tag for a local js file, along with its modification
	# time (as a uri parameter), to prevent caching of *modified* file
	#
	# @param string $filename File name
	#
	# @return null
	#
	function include_jscript($filename) {
		try {
			$str = "<script type='text/javascript' src='js/$filename";
			$str .= "?t=" . filemtime(FCPATH . "/js/$filename") . "'></script>" . PHP_EOL;

			echo $str;
		} catch (\Throwable $e) {
			return;
		}
	}
}


###################################################################################################
if (!function_exists('insert_hspace')) {
	#
	# Insert horizontal space
	#
	# Insert a horizontal html <div>, with a 100% width and a desired height
	# (all units are accepted: px, rem, etc...)
	#
	# @param string $cssvalue the desired css 'height' property value
	#
	# @return null
	#
	function insert_hspace($cssvalue) {
		echo "<div style='width:100%;height:$cssvalue;'></div>";
	}
}
