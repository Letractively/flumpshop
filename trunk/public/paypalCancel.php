<?php require_once("./preload.php"); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="../Templates/Default.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title><?php echo $_GLOBALS['siteName'];?> | Transaction Cancelled</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
<link href="<?php echo $config->getNode('paths','root');?>/style/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $config->getNode('paths','root');?>/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo $config->getNode('paths','root');?>/js/jqueryui.js"></script>
<script type="text/javascript" src="<?php echo $config->getNode('paths','root');?>/js/jeditable.js"></script>
<link href="<?php echo $config->getNode('paths','root');?>/style/jquery.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $config->getNode('paths','root');?>/style/jquery-overrides.css" rel="stylesheet" type="text/css" />
</head>

<body class="oneColElsCtr">

<div id="container">
  <div id="mainContent" class="ui-widget">
  <h1><a href="<?php echo $config->getNode('paths','root');?>/index.php"><?php echo $config->getNode('messages', 'name');?></a></h1>
  <div class="ui-widget-content" style="float: right; width: auto; padding-right: 1em;"><a href='<?php echo $config->getNode('paths','root');?>/basket.php'><span class="ui-icon ui-icon-cart"></span><?php echo $basket->getItems(); ?> Item(s) | &pound;<?php echo $basket->getFriendlyTotal(); ?></a></div>
  <?php if ($_PRINTDATA) echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-alert'></span>Debug Mode Enabled.</div>";?>
  <!-- InstanceBeginEditable name="docbody" -->
  <?php
  //Unlock Basket for Editing
  $basket->unlock();
  echo $config->getNode("messages", "transactionCancelled");
  ?>
  <!-- InstanceEndEditable -->
	<!-- end #mainContent --></div>
<!-- end #container --></div>
<?php echo $config->getNode('messages','footer');?>
<!-- InstanceBeginEditable name="scripts" -->
<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
