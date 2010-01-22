<?php require_once dirname(__FILE__)."/../../includes/vars.inc.php"; require_once "./Upgrade.class.php";
$latestVersion = file_get_contents("http://flumpshop.googlecode.com/svn/updater/version.txt");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--
Design by Free CSS Templates
http://www.freecsstemplates.org
Released for free under a Creative Commons Attribution 2.5 License
-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Flumpshop Upgrade</title>
<link href="default.css" rel="stylesheet" type="text/css" />
<link href="../../style/jquery.css" rel="stylesheet" type="text/css" />
<link href="../../style/jquery-overrides.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/jquery.js"></script>
</head>
<body>
<div id="header">
	<h1>Flumpshop</h1>
	<h2>The living online shop</h2>
	<!--<ul>
		<li><a href="#" accesskey="1" title="">Homepage</a></li>
		<li><a href="#" accesskey="2" title="">My Blog</a></li>
		<li><a href="#" accesskey="3" title="">Photo Gallery</a></li>
		<li><a href="#" accesskey="4" title="">Favorite Sites</a></li>
		<li><a href="#" accesskey="5" title="">Contact Me</a></li>
	</ul>-->
</div>
<div id="content">
	<div id="colOne">
		<h3>Upgrade</h3><?php
		if (file_exists($config->getNode("paths","offlineDir")."/upgrade_v".$latestVersion.".fml")) {
			$package = unserialize(base64_decode(file_get_contents($config->getNode("paths","offlineDir")."/upgrade_v".$latestVersion.".fml")));
			$upgrade = $package['upgrade'];
		?><div class="bg1">
			<ul>
				<li class="first"><a href="javascript:">Important Information</a></li>
				<li><a href="javascript:">Configure New Features</a></li>
				<li><a href="javascript:">Upgrade</a></li>
                <li><a href="javascript:">Finish</a></li>
			</ul>
		</div>
	</div>
	<div id="colTwo"><?php
    if (!isset($_GET['page'])) {
		?><div class="bg2">
			<h2><em>Flumpshop</em> Upgrade</h2>
			<p>This wizard will upgrade the Flumpshop client from v<?php echo $config->getNode("site","version");?> to v<?php echo $upgrade->newVersion();?>. Once you start this process, the Flumpnet Robot, and your Flumpshop Site, will be unavailable until the upgrade is complete. Please review all the latest details below, make any necessary changes, and if you're sure, click "Start Upgrade".</p>
		</div>
		<h3>Upgrade Notices</h3>
		<div class="bg1">
			<p><?php echo $upgrade->getNotes();?></p>
		</div>
		<h3>Flumpstream Feed</h3>
		<div class="bg1">
			<p>The Flumpstream News Feed is Unavailable</p>
		</div>
        <div class="bg1">
        	<p><input type="button" onclick="window.location = '?page=features';" value="Start Upgrade" class="ui-widget-content" /></p>
        </div><?php
	} elseif ($_GET['page'] == "features") {
		//Disable Site
		$config->setNode("site","enabled",false);
		?><div class="bg2">
        	<div class="ui-state-highlight"><span class="ui-icon ui-icon-power"></span>The site has been set to maintenance mode.</div>
        	<h2>New Features</h2>
            <p>Since the last upgrade, it is possible that there is some additional configuration necessary. If so, then you will be asked to enter the new settings below.<form action="?page=upgrade" method="post" id="form"><?php
			//Print out change Configuration Variables
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
			}?><input type="submit" value="Upgrade" onclick="this.disabled = true; $('#form').submit();" /></form><?php
	}//END New Conf Vars
	elseif ($_GET['page'] == "upgrade") {
		//Commit the upgrade
		echo "Saving Configuration Changes...<br />";
		$newConf = $upgrade->getConfUpdate();
		foreach ($newConf as $item) {
			if (!$config->isTree($item['tree'])) {
				$config->addTree($item['tree']);
			}
			$config->setNode($item['tree'],$item['node'],$item['value'],$item['name']);
		}
		foreach ($_POST as $key => $value) {
			$key = explode("|",$key);
			$config->setNode($key[0], $key[1], $value);
		}
		echo "Updating Files...<br />";
		//Update Files
		foreach ($package['package'] as $dirname => $dir) {
			if (!is_dir($dirname)) mkdir($dirname);
			foreach ($dir as $file => $contents) {
				$fp = fopen($dirname."/".$file,"w+");
				fwrite($fp, $contents);
				fclose($fp);
			}
		}
		echo "Updating Database...<br />";
		require_once "../../includes/Database.class.php";
		$_SETUP = false;
		$dbConn = db_factory();
		$sql = $upgrade->getSQL();
		if (!empty($sql)) $dbConn->query($upgrade->getSQL());
		echo "Updating Version...<br />";
		$config->setNode("site","version",$upgrade->newVersion());
		echo "Bringing site out of Maintenance Mode...<br />";
		$config->setNode("site","enabled",true);
		echo "Cleaning Up...<br />";
		unlink($config->getNode("paths","offlineDir")."/upgrade_v".$latestVersion.".fml");
		echo "Upgrade Complete. <a href='?page=finish'>Click Here to continue</a>";
	} elseif ($_GET['page'] == "finish") {
		?><div class='bg2'><h2>Upgrade Complete</h2><p>Your Flumpshop Client has now been upgraded to v<?php echo $config->getNode('site','version');?>.<?php
	}
	?></div><?php
} else {
	//Upgrade Package hasn't been downloaded yet
	?><div class="bg1">
    Current Flumpshop Version: <?php echo $config->getNode("site","version");?><br />
    Latest Flumpshop Version: <?php echo $latestVersion;?>
    </div></div>
    <div id="colTwo">
    	<div class="bg1">
        	<h2>Upgrade</h2>
			<p>The latest upgrade file is now being downloaded. If you are having issues with this feature, please download the file http://flumpshop.googlecode.com/svn/updater/upgrade_<?php echo $latestVersion;?>.fml file and place it in the offline directory.</p>
            <p>Remember that PHP must have WRITE access to the Flumpshop root directory and all subdirectories for this process.</p>
            <p><center id="downloader"><img src="../../images/loading.gif" /><br />Downloading upgrade_v<?php echo $latestVersion;?>.fml</center></p>
            <script type="text/javascript">$('#downloader').load('downloader.php?file=upgrade_v<?php echo $latestVersion;?>.fml');</script>
        </div>
	</div><?php
}?></div>
<div id="footer">
	<p>Copyright (c) 2009-2010 <a href='http://www.theflump.com'>Flumpnet</a>. All Rights Reserved. Upgrader Developer Preview Design by <a href="http://www.freecsstemplates.org/">Free CSS Templates</a>.</p>
</div>
</body>
</html>