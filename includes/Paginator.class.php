<?php
class Paginator {
	
	function paginate($cpage,$perPage,$items,$urlprefix,$ajax = NULL,$rewrite = false) {
		global $config;
		
		$pages = ceil($items/$perPage);
		
		if (stristr($urlprefix,"?")) $join = "&"; else $join = "?";
		$return = "<div class='ui-state-default' style='text-align: center;'>";
		if ($cpage != 1) {
			//First/Previous page links
			if ($ajax != NULL) {
				//Ajax mode
				$return .= "<a href='javascript:void(0);' onclick='$(\"#".$ajax."\").html(loadMsg(\"Loading Content...\")).load(\"".$urlprefix.$join."page=1\");'>".$config->getNode('messages','firstPage')."</a>&nbsp;";
				$return .= "<a href='javascript:void(0);' onclick='$(\"#{$ajax}\").html(loadMsg(\"Loading Content...\")).load(\"".$urlprefix.$join."page=".($cpage-1)."\");'>".$config->getNode('messages','previousPage')."</a>&nbsp;";
			} elseif ($rewrite) {
				//URL Rewrite Mode
				$return .= "<a href='".$urlprefix."1'>".$config->getNode('messages','firstPage')."</a>&nbsp;";
				$return .= "<a href='".$urlprefix.($cpage-1)."'>".$config->getNode('messages','previousPage')."</a>&nbsp;";
			} else {
				//Normal
				$return .= "<a href='".$urlprefix.$join."page=1'>".$config->getNode('messages','firstPage')."</a>&nbsp;";
				$return .= "<a href='".$urlprefix.$join."page=".($cpage-1)."'>".$config->getNode('messages','previousPage')."</a>&nbsp;";
			}
		}
		
		for ($i = $cpage-10; $i < $cpage; $i++) {
			//Previous 10 pages links
			if ($i > 0) {
				if ($ajax != NULL) {
					//AJAX
					$return .= "<a href='javascript:void(0);' onclick='$(\"#$ajax\").html(loadMsg(\"Loading Content...\")).load(\"".$urlprefix.$join."page=$i\");'>".$i."</a>&nbsp;";
				} elseif ($rewrite) {
					//Rewrite
					$return .= "<a href='".$urlprefix."$i'>".$i."</a>&nbsp;";
				} else {
					//Normal
					$return .= "<a href='".$urlprefix.$join."page=$i'>".$i."</a>&nbsp;";
				}
			}
		}
		
		//Current Page
		$return .= "<span class='ui-state-active'>".$cpage."</span>&nbsp;";
		
		//Next 10 pages links
		for ($i = $cpage+1; $i <= $pages && $i < $cpage+10; $i++) {
			if ($ajax != NULL) {
				//Ajax
				$return .= "<a href='javascript:void(0);' onclick='$(\"#$ajax\").html(loadMsg(\"Loading Content...\")).load(\"".$urlprefix.$join."page=$i\");'>".$i."</a>&nbsp;";
			} elseif ($rewrite) {
				//Rewrite
				$return .= "<a href='".$urlprefix.$i."'>".$i."</a>&nbsp;";
			} else {
				//Normal
				$return .= "<a href='".$urlprefix.$join."page=$i'>".$i."</a>&nbsp;";
			}
		}
		
		if ($cpage != $pages) {
			//Next and last pages links
			if ($ajax != NULL) {
				//Ajax
				$return .= "<a href='javascript:void(0);' onclick='$(\"#$ajax\").html(loadMsg(\"Loading Content...\")).load(\"".$urlprefix.$join."page=".($cpage+1)."\");'>".$config->getNode('messages','nextPage')."</a>&nbsp;";
				$return .= "<a href='javascript:void(0);' onclick='$(\"#$ajax\").html(loadMsg(\"Loading Content...\")).load(\"".$urlprefix.$join."page=$pages\");'>".$config->getNode('messages','lastPage')."</a>&nbsp;";
			} elseif ($rewrite) {
				//Rewrite
				$return .= "<a href='".$urlprefix.($cpage+1)."'>".$config->getNode('messages','nextPage')."</a>&nbsp;";
				$return .= "<a href='".$urlprefix.$pages."'>".$config->getNode('messages','lastPage')."</a>&nbsp;";
			} else {
				//Normal
				$return .= "<a href='".$urlprefix.$join."page=".($cpage+1)."'>".$config->getNode('messages','nextPage')."</a>&nbsp;";
				$return .= "<a href='".$urlprefix.$join."page=$pages'>".$config->getNode('messages','lastPage')."</a>&nbsp;";
			}
		}
		$return .= "</div>";
		return $return;
	}
}
?>