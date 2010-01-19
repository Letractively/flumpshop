<?php
if (!function_exists('file_put_contents')) {
	function file_put_contents($file, $contents) {
		$fp = fopen($file,"w+");
		if (!fwrite($fp,$contents)) return false;
		fclose($fp);
		return true;
	}
}
?>