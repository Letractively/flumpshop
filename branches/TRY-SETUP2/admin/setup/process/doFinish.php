<?php
require_once dirname(__FILE__)."/../header.inc.php";
//Save the configuration object
file_put_contents(dirname(__FILE__)."/status.txt", "Saving Configuration Object");
sleep(1);
file_put_contents(dirname(__FILE__)."/../../../conf.txt", $_SESSION['config']->getNode('paths','offlineDir')."/conf.txt");
file_put_contents($_SESSION['config']->getNode('paths','offlineDir')."/conf.txt", serialize($_SESSION['config']));
file_put_contents(dirname(__FILE__)."/status.txt", "Copying Setup Files");
sleep(10);
require_once dirname(__FILE__)."/../footer.inc.php";
?>