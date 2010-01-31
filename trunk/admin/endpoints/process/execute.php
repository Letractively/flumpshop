<?php require_once dirname(__FILE__)."/../../../preload.php";
if (!isset($_SESSION['adminAuth']) or !$_SESSION['adminAuth']) die("Authentication Failed.");
foreach(explode(";\n",$_POST['query']) as $query) {$dbConn->query($query);}
echo "Query sent. I'm afraid I can't tell you the result of it though.";
?>