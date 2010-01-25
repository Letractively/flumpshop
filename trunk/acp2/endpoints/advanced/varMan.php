<?php
$ajaxProvider = true;
require_once dirname(__FILE__)."/../../../preload.php";
$disabled = "";
if (!$config->getEditable()) {
	?>
    <div class="ui-state-error"><span class="ui-icon ui-icon-alert"></span>I don't have permission to save any changes.</div>
    <?php
	$disabled = " disabled='disabled'";
}
?>
<form action='./endpoints/process/saveConf.php' method="POST" name='varForm' id='varForm'>
<?php
//Section input form
foreach ($config->getTrees() as $tree) {
	if ($tree != "orderstatus" && $tree != "temp") {
		echo "<fieldset><legend>".$config->getFriendName($tree)."</legend><table>";
			foreach ($config->getNodes($tree) as $pathNode) {
				$class = "";
				$name = $config->getFriendName($tree,$pathNode);
				$value = $config->getNode($tree,$pathNode);
				if (is_bool($value)) {
					if ($value == true) $checked = " checked='checked'"; else $checked = "";
					echo "<tr class='ui-widget-content'><td width='250'><label for='$tree|$pathNode'>$name</label></td><td><input type='checkbox' name='$tree|$pathNode' id='$tree|$pathNode' class='ui-state-default'$checked /></td></tr>";
				} else {
					if (strlen($value) >= 70) {
						//Textarea
						$value = str_replace(array("'","\\"),
											 array("&apos;",""),
											 htmlentities($value));
						$class .= get_valid_class($tree,$pathNode);
						echo "<tr class='ui-widget-content'><td width='250'><label for='$tree|$pathNode'>$name</label></td><td><textarea name='$tree|$pathNode' id='$tree|$pathNode' class='".$class."ui-state-default' style='width: 400px; height: 150px;'>$value</textarea></td></tr>";

					} else {
						$value = str_replace("'","&apos;",htmlentities($value));
						$class .= get_valid_class($tree,$pathNode);
						echo "<tr class='ui-widget-content'><td width='250'><label for='$tree|$pathNode'>$name</label></td><td><input type='text' name='$tree|$pathNode' id='$tree|$pathNode' value='$value' class='".$class."ui-state-default' /></td></tr>";
					}
				}
			}
			echo '</table></fieldset>';
	}
}
?>
<input type="submit" value="Save"<?php echo $disabled;?> />
</form>