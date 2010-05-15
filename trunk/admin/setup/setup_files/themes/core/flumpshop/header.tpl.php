<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $title; ?></title>
<?php
echo $meta_tags;
echo $css_links;
echo $js_links;
echo $plugin_includes;
?>
</head>
<body>
<table id="main_container">
	<tr>
		<td>
			<table id="header" class="ui-corner-all">
				<tr>
					<td><h1 id="site_name"><a href="<?php echo $config->getNode("paths","root")?>"><?php echo $config->getNode("messages","name")?></a></h1></td>
					<td id="search_container"><form action='<?php echo $config->getNode('paths','root');?>/search.php' method='get' id='search_form'><input type='text' name='q' id='q' value='Search...' onfocus='if(this.value=="Search..."){this.value="";}' onblur='if(this.value==""){this.value="Search...";}' /><input type='submit' id='search_submit' title='Click to search' value='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' /></form></td>
				</tr>
				<tr>
					<td id="site_tagline"><?php echo $config->getNode("messages","tagline")?></td>
					<td id="tabs"><ul><?php echo $tab_links;?></ul></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr id="nav_container">
		<td class="ui-corner-all"><?php echo $navigation_links;?></td>
	</tr>