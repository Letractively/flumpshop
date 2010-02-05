<?php
error_reporting(E_ALL);
require "./Upgrade.class.php";
$upgrade = new Upgrade();

//Easy Part
$upgrade->setPrevRevision($_POST['prevRevision']);
$upgrade->setVersion($_POST['version']);
$upgrade->setNotes($_POST['notes']);
$upgrade->setSQL($_POST['sql']);

//Creates a two dimensional array of new or updated configuration variables
$trees = explode("\n",$_POST['confVarsTree']);
$nodes = explode("\n",$_POST['confVarsNode']);
$defaults = explode("\n",$_POST['confVarsVal']);
$names = explode("\n",$_POST['confVarsName']);

$config = array();
for ($i = 0; isset($trees[$i]); $i++) {
	$config[$i]['tree'] = $trees[$i];
	$config[$i]['node'] = $nodes[$i];
	$config[$i]['value'] = $defaults[$i];
	$config[$i]['name'] = $names[$i];
}
if (!empty($config[0]['tree'])) {
	$upgrade->setConfUpdate($config);
}
$package = array();
//Upgrade Object Generated, now package files
$dirs = array("../..");
while ($dir = array_pop($dirs)) {
	$handle = opendir(dirname(__FILE__)."/$dir");
	while ($file = readdir($handle)) {
		if (is_dir(dirname(__FILE__)."/$dir/$file") && $file != "." && $file != "..") {
			array_push($dirs,$dir."/$file");
		} elseif (preg_match("/(php|css|js|png|jpg|gif|mp3)$/i",$file)) {//Only include PHP, CSS, Image, Audio and JS Files
			if (!is_dir(dirname(__FILE__)."/$dir/$file")) {
				//Get the path for the previous file in the repository
				$svnPath = "http://flumpshop.googlecode.com/svn-history/r".$upgrade->getPrevRevision()."/trunk/".str_replace("../../","",$dir."/".$file);
				$svnFile = file_get_contents($svnpath);
				//MD5 hashes are at risk from a collision, checking both the MD5 and SHA1 of a file prevents any collision
				if((md5($svnFile)!=md5(file_get_contents((dirname(__FILE__)."/$dir/$file")() && (sha1(file_get_contents(($svnFile)!=sha1(dirname(__FILE__)."/$dir/$file"))))
				{
					$package[$dir][$file] = file_get_contents(dirname(__FILE__)."/$dir/$file");
				}
			}
		}
	}
}
header("Content-Disposition: attachment; filename=upgrade_v".$upgrade->newVersion().".fml");
$data['upgrade'] = $upgrade;
$data['package'] = $package;
echo base64_encode(serialize($data));
?>