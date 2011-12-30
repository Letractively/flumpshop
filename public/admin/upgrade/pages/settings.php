<?php
require_once "header.inc.php";
$config->setNode("site","enabled",false);
?><div class="ui-state-highlight"><span class="ui-icon ui-icon-power"></span>The site has been set to maintenance mode.</div>
<div class="ui-state-highlight"><span class="ui-icon ui-icon-power"></span>The Flumpnet robot is unavailable.</div>
<h2>New Features</h2>
<p>Review any information below before continuing. Incorrect settings may break the installation.
<form action="upgrade.php" method="post" id="form"><?php
//Print out changed Configuration Variables
$newConf = $upgrade->getConfUpdate();
$lastTree = "nonExistantTree";
foreach ($newConf as $item) {
    if ($item['tree'] != $lastTree) {
        echo "</p></div><div class='bg1'><h3>".$item['tree']."</h3><p>";
        $lastTree = $item['tree'];
    }
    $tree = $item['tree'];
    $name = $item['name'];
    $pathNode = $item['node'];
    //Makes sure right data type is set
    //\r is sometimes kept in
    if (preg_match("/^true(\r)?$/i",$item['value'])) $value = true;
    elseif (preg_match("/^false(\r)?$/i",$item['value'])) $value = false;
    else $value = preg_replace("/\r$/","",$item['value']);
    //Create Form Field
    if (is_bool($value)) {
        if ($value == true) $checked = " checked='checked'"; else $checked = "";
        echo "<label for='$tree|$pathNode'>$name</label><input type='checkbox' name='$tree|$pathNode' id='$tree|$pathNode' class='ui-state-default'$checked /><br />";
    } else {
        if (strlen($value) >= 70) {
            //Textarea
            $value = str_replace(array("'","\\"),
                                 array("&apos;",""),
                                 htmlentities($value));
            echo "<label for='$tree|$pathNode'>$name</label><br /><textarea name='$tree|$pathNode' id='$tree|$pathNode' style='width: 400px; height: 150px;'>$value</textarea><br />";

        } else {
            $value = str_replace("'","&apos;",htmlentities($value));
            echo "<label for='$tree|$pathNode'>$name</label><input type='text' name='$tree|$pathNode' id='$tree|$pathNode' value='$value' /><br />";
        }
    }
}?><input type="submit" value="Upgrade" onclick="this.disabled = true; $('#form').submit();" /></form>