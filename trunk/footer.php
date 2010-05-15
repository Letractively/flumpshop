<div id="dialog" class="ui-helper-hidden"></div><?php //Needed for UI Framework
if ($config->getNode("site","templateMode") == "core" and 
	(file_exists($config->getNode("paths","offlineDir")."/themes/core/flumpshop/".PAGE_TYPE.".footer.tpl.php") or
	file_exists($config->getNode("paths","offlineDir")."/themes/core/flumpshop/footer.tpl.php"))) {
	//New Template Mechanism
	// Footer Links
	$footer_links = "";
	//Send Feedback
	if ($config->getNode('site','sendFeedback')) {
	  $footer_links .= "<a href='".$config->getNode('paths','root')."/reportbug.php'>".$config->getNode("messages","sendFeedbackHeader")."</a> &middot; ";
	}
	$footer_links .= "<a href='".$config->getNode('paths','root')."/legal/privacy.php'>".$config->getNode("messages","privacyPolicyHeader")."</a> &middot; ";
	
	$footer_links .= "<a href='".$config->getNode('paths','root')."/legal/terms.php'>".$config->getNode("messages","termsConditionsHeader")."</a> &middot; ";
	$footer_links .= "<a href='".$config->getNode('paths','root')."/legal/disclaimer.php'>".$config->getNode("messages","disclaimerHeader")."</a>";
	if (isset($_SESSION['adminAuth']) && $_SESSION['adminAuth']) {
		//Stats
		$footer_links .= " &middot; Visitors: ";
		$footer_links .= $dbConn->rows($dbConn->query("SELECT basket FROM `sessions`"));
	}
	
	// Footer Scripts
	$footer_scripts = '<script type="text/javascript" src="'.$config->getNode("paths","root").'/js/footer.php"></script>';
	
	//Plugin Includes
	// Create a second OB
	ob_start();
	$dir = opendir($config->getNode('paths','offlineDir')."/plugins");
	while ($module = readdir($dir)) {
		if (file_exists($config->getNode('paths','offlineDir')."/plugins/".$module."/includes/footer.inc.php")) {
			include $config->getNode('paths','offlineDir')."/plugins/".$module."/includes/footer.inc.php";
		}
	}
	$plugin_includes = ob_get_clean(); //Get OB Content and exit OB
	
	if (file_exists($config->getNode("paths","offlineDir")."/themes/core/flumpshop/".PAGE_TYPE.".footer.tpl.php")) {
		require $config->getNode("paths","offlineDir")."/themes/core/flumpshop/".PAGE_TYPE.".footer.tpl.php";
	} else {
		require $config->getNode("paths","offlineDir")."/themes/core/flumpshop/footer.tpl.php";
	}
} else {
	//Legacy Template
	?></div><!--End Content Container--><?php require_once dirname(__FILE__)."/preload.php";
	echo "<!--Page Generated in ".(microtime_float()-$time_start)." Seconds-->";
	
	/*PLUGINS*/
	//Each plugin that has /includes/footer.inc.php will have an option displayed here
	$dir = opendir($config->getNode('paths','offlineDir')."/plugins");
	while ($module = readdir($dir)) {
		if (file_exists($config->getNode('paths','offlineDir')."/plugins/".$module."/includes/footer.inc.php")) {
			include $config->getNode('paths','offlineDir')."/plugins/".$module."/includes/footer.inc.php";
		}
	}
	?><br class="clear" />
	<div id='footer'><p id="footer_text"><?php echo $config->getNode('messages','footer');?></p>
	<p id="footer_links"><?php
	  //Send Feedback
	  if ($config->getNode('site','sendFeedback')) {
		?><a href='<?php echo $config->getNode('paths','root');?>/reportbug.php'>Send Feedback</a> &middot; <?php
		}
	?><a href='<?php echo $config->getNode('paths','root');?>/legal/privacy.php'>Privacy Policy</a> &middot;
	<a href='<?php echo $config->getNode('paths','root');?>/legal/terms.php'>Terms and Conditions</a> &middot;
	<a href='<?php echo $config->getNode('paths','root');?>/legal/disclaimer.php'>Disclaimer</a><?php
	if (isset($_SESSION['adminAuth']) && $_SESSION['adminAuth']) {
		//Stats
		echo " &middot; Visitors: ";
		echo $dbConn->rows($dbConn->query("SELECT basket FROM `sessions`"));
	}
	?>&nbsp;&nbsp;</p><!--Site Designed by Jake Mitchell. Programmed by Lloyd Wallis and John Maydew.-->
	</div><!--End Footer--></div><!--End Container-->
	<script type="text/javascript" src="<?php echo $config->getNode("paths","root")."/js/footer.php";?>"></script>
	<?php
	}

if ($cron) {
	ob_flush();flush(); //Let page output whilst this runs
	echo "<div style='display:none;'>";
	require_once dirname(__FILE__)."/admin/endpoints/process/cron.php";
	echo "</div>";
}
?>