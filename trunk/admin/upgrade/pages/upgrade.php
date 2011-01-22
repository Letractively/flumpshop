<?php

require_once "header.inc.php";


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
	//Workaround: Fixes issue with packager
	$dirname = str_replace("C:/inetpub/wwwroot","../../../",$dirname);
	
    if (!is_dir($dirname)) {
		$path = explode("/",$dirname);
		$tempdir = "";
		foreach ($path as $subdir) {
			//Create each directory above
			$tempdir .= $subdir."/";
			if (preg_match("/^(\.\.\/(\/)?)*$/",$tempdir)) continue; //Don't try to create a series of ../
			if (!is_dir($dirname)) {
				echo "Creating $tempdir..<br />";
				mkdir($tempdir);
			}
		}
	}

    foreach ($dir as $file => $contents) {
		echo $dirname."/".$file."<br />";
        $fp = fopen($dirname."/".$file,"w+");

        fwrite($fp, $contents);

        fclose($fp);

    }

}

echo "Updating Database...<br />";

$sql = $upgrade->getSQL();

if (!empty($sql)) $dbConn->query($upgrade->getSQL());

echo "Updating Version...<br />";

$config->setNode("site","version",$upgrade->newVersion());

echo "Bringing site out of Maintenance Mode...<br />";

$config->setNode("site","enabled",true);

echo "Cleaning Up...<br />";

unlink($config->getNode("paths","offlineDir")."/upgrade_v".$latestVersion.".fml");

echo "Upgrade Complete. <a href='finish.php'>Click Here to continue</a>";

?>