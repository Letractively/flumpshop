<?php require_once("./preload.php"); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- TemplateBeginEditable name="doctitle" -->
<title><?php echo $config->getNode('site', 'name');?></title>
<!-- TemplateEndEditable -->
<!-- TemplateBeginEditable name="head" -->
<!-- TemplateEndEditable -->
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
  <!-- TemplateBeginEditable name="docbody" -->
    <h1> Main Content </h1>
    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Praesent aliquam,  justo convallis luctus rutrum, erat nulla fermentum diam, at nonummy quam  ante ac quam. Maecenas urna purus, fermentum id, molestie in, commodo  porttitor, felis. Nam blandit quam ut lacus. Quisque ornare risus quis  ligula. Phasellus tristique purus a augue condimentum adipiscing. Aenean  sagittis. Etiam leo pede, rhoncus venenatis, tristique in, vulputate at,  odio. Donec et ipsum et sapien vehicula nonummy. Suspendisse potenti. Fusce  varius urna id quam. Sed neque mi, varius eget, tincidunt nec, suscipit id,  libero. In eget purus. Vestibulum ut nisl. Donec eu mi sed turpis feugiat  feugiat. Integer turpis arcu, pellentesque eget, cursus et, fermentum ut,  sapien. Fusce metus mi, eleifend sollicitudin, molestie id, varius et, nibh.  Donec nec libero.</p>
    <h2>H2 level heading </h2>
    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Praesent aliquam,  justo convallis luctus rutrum, erat nulla fermentum diam, at nonummy quam  ante ac quam. Maecenas urna purus, fermentum id, molestie in, commodo  porttitor, felis. Nam blandit quam ut lacus. Quisque ornare risus quis  ligula. Phasellus tristique purus a augue condimentum adipiscing. Aenean  sagittis. Etiam leo pede, rhoncus venenatis, tristique in, vulputate at, odio.</p>
  <!-- TemplateEndEditable -->
	<!-- end #mainContent --></div>
<!-- end #container --></div>
<?php echo $config->getNode('messages','footer');?>
<!-- TemplateBeginEditable name="scripts" -->
<!-- TemplateEndEditable -->
</body>
</html>
