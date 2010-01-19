<?php
class Paginator {
	
	function paginate($cpage,$perPage,$items,$urlprefix,$ajax = NULL) {
		global $config;
		
		$pages = ceil($items/$perPage);
		
		if (stristr($urlprefix,"?")) $join = "&"; else $join = "?";
		$return = "<div class='ui-state-default' style='text-align: center;'>";
		if ($cpage != 1) {
			if ($ajax != NULL) {
				$return .= "<a href='javascript:void(0);' onclick='$(\"#".$ajax."\").load(\"".$urlprefix.$join."page=1\");'>".$config->getNode('messages','firstPage')."</a>&nbsp;";
				$return .= "<a href='javascript:void(0);' onclick='$(\"#{$ajax}\").load(\"$urlprefix$joinpage=".($cpage-1)."\");'>".$config->getNode('messages','previousPage')."</a>&nbsp;";
			} else {
				$return .= "<a href='".$urlprefix.$join."page=1'>".$config->getNode('messages','firstPage')."</a>&nbsp;";
				$return .= "<a href='".$urlprefix.$join."page=".($cpage-1)."'>".$config->getNode('messages','previousPage')."</a>&nbsp;";
			}
		}
		
		for ($i = $cpage-10; $i < $cpage; $i++) {
			if ($i > 0) {
				if ($ajax != NULL) {
					$return .= "<a href='javascript:void(0);' onclick='$(\"#$ajax\").load(\"".$urlprefix.$join."page=$i\");'>".$i."</a>&nbsp;";
				} else {
					$return .= "<a href='".$urlprefix.$join."page=$i'>".$i."</a>&nbsp;";
				}
			}
		}
		
		$return .= "<span class='ui-state-active'>".$cpage."</span>&nbsp;";
		
		for ($i = $cpage+1; $i <= $pages && $i <$cpage+10; $i++) {
			if ($ajax != NULL) {
				$return .= "<a href='javascript:void(0);' onclick='$(\"#$ajax\").load(\"".$urlprefix.$join."page=$i\");'>".$i."</a>&nbsp;";
			} else {
				$return .= "<a href='".$urlprefix.$join."page=$i'>".$i."</a>&nbsp;";
			}
		}
		
		if ($cpage != $pages) {
			if ($ajax != NULL) {
				$return .= "<a href='javascript:void(0);' onclick='$(\"#$ajax\").load(\"".$urlprefix.$join."page=".($cpage+1)."\");'>".$config->getNode('messages','nextPage')."</a>&nbsp;";
				$return .= "<a href='javascript:void(0);' onclick='$(\"#$ajax\").load(\"".$urlprefix.$join."page=$pages\");'>".$config->getNode('messages','lastPage')."</a>&nbsp;";
			} else {
				$return .= "<a href='".$urlprefix.$join."page=".($cpage+1)."'>".$config->getNode('messages','nextPage')."</a>&nbsp;";
				$return .= "<a href='".$urlprefix.$join."page=$pages'>".$config->getNode('messages','lastPage')."</a>&nbsp;";
			}
		}
		$return .= "</div>";
		return $return;
	}
}
?>