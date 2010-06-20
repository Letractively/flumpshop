<?php

class FormHelper {
	
	
	
	function countrySelector($id = 'countrySelector',$supportedOnly = false) {
		global $config, $dbConn;
		
		if ($supportedOnly) {
			//Supported
			if ($config->isNode('cache','supportedCountrySelector')) {
				return str_replace('[[name]]',$id,$config->getNode('cache','supportedCountrySelector')); //Use cache if available
			} else {
				ob_start();
				
				$result = $dbConn->query("SELECT * FROM `country` WHERE supported=1 ORDER BY name ASC");
				echo '<select name="'.$id.'" id="'.$id.'" class="ui-widget-content required"><option value=""></option>';
				while ($row = $dbConn->fetch($result)) {
					echo '<option value="'.$row['iso'].'">'.$row['name'].'</option>';
				}
				echo '</select>';
				
				$return = ob_get_clean();
				$cache = str_replace($id,"[[name]]",$return);
				$config->setNode('cache','supportedCountrySelector',$cache);
				return $return;
			}
		} else {
			//All
			if ($config->isNode('cache','fullCountrySelector')) {
				return str_replace('[[name]]',$id,$config->getNode('cache','fullCountrySelector')); //Use cache if available
			} else {
				ob_start();
				
				$result = $dbConn->query("SELECT * FROM `country` ORDER BY name ASC");
				echo '<select name="'.$id.'" id="'.$id.'" class="ui-widget-content required" style="width: 150px;"><option value=""></option>';
				while ($row = $dbConn->fetch($result)) {
					if ($row['iso'] == $config->getNode('site','country')) {
						echo '<option value="'.$row['iso'].'" selected="selected">'.$row['name'].'</option>';
					} else
					echo '<option value="'.$row['iso'].'">'.$row['name'].'</option>';
				}
				echo '</select>';
				
				$return = ob_get_clean();
				$cache = str_replace($id,'[[name]]',$return);
				$config->setNode('cache','fullCountrySelector',$cache);
				return $return;
			}
		}
	}
	
}
?>