<?php
if (!function_exists('file_get_contents')) {
	function file_get_contents($file) {
		$fp = fopen($file,"w+");
		return fread($fp,filesize($file));
		fclose($fp);
		return true;
	}
}
?>