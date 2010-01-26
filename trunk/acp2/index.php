<?php
require_once "../preload.php";
if (isset($_GET['frame'])) {
	if ($_GET['frame'] == "leftFrame") {
		//Left Frame
		?><html>
        	<head>
            <link href="../style/jquery.css" rel="stylesheet" type="text/css" />
            <link href="style-nav.css" rel="stylesheet" type="text/css" />
            <script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
            <script type="text/javascript" language="javascript" src="../js/jqueryui.js"></script>
            </head>
            <body>
            	<center><img src="images/logo.jpg" />
                <div class="header">flump<span class='header2'>shop</span> <?php echo $config->getNode("site","version");?></div>
                Powered by Flumpnet<br />
                <!--<a href="javascript:void(0);">Expand All</a> | <a href="javascript:void(0);"> Collapse All</a><br />--><br />
                <div id="navContainer">
                <div id="navAccordion">
                	<h3>Flumpshop Options</h3>
                    <div>
                    	<h4>Create</h4>
                    	<a href='endpoints/create/newItem.php' target="main">Item</a>
                        <a href='endpoints/create/newCategory.php' target="main">Category</a>
                        <a href='endpoints/create/newNews.php' target="main">News Post</a>
                        <a href='endpoints/create/newTechHelp.php' target="main">Technical Tips Post</a>
                        <h4>Edit</h4>
                        <a href='endpoints/edit/editItems.php' target="main">Items</a>
                        <a href='endpoints/edit/editCategory.php' target="main">Categories</a>
                        <a href='endpoints/edit/editPageContent.php?pageid=privacyPolicy' target="main">Privacy Policy</a>
                        <a href='endpoints/edit/editPageContent.php?pageid=disclaimer' target="main">Disclaimer</a>
                        <a href='endpoints/edit/editPageContent.php?pageid=termsConditions' target="main">Terms and Conditions</a>
                        <a href='endpoints/edit/editPageContent.php?pageid=homePage' target="main">Home Page</a>
                        <a href='endpoints/edit/editPageContent.php?pageid=aboutPage' target="main">About Page</a>
                        <a href='endpoints/edit/editPageContent.php?pageid=contactPage' target="main">Contact Page</a>
                        <a href='endpoints/edit/editFeatured.php' target="main">Featured Item</a>
                        <h4>Orders</h4>
                        <a href='endpoints/orders/listOrders.php?filter=active' target="main">Active</a>
                        <a href='endpoints/orders/listOrders.php?filter=closed' target="main">Closed</a>
                    </div><br /><br />
                    <h3>Template Options</h3>
                    <div>Hey, the Flumpnet robot can't remember how to do this right now. Try again later!</div><br /><br />
                    <h3>Maintenance Options</h3>
                    <div>
                    	<a href="endpoints/process/cron.php" target="main">Cron Script</a>
                        <a href="logs" target="main">Logs</a>
                        <a href="adminendpoints/advanced/bugs.php" target="main">Bugs</a>
                        <a href="javascript:void(0);" onClick="loadVarMan();" target="main">Configuration Manager</a>
                    </div>
                </div>
                </div>
                </center>
                <script type="text/javascript">
				$(document).ready(function() {$('#navAccordion').accordion({collapsible: true, active: false, autoHeight: false, icons: {'header': 'ui-icon-circle-arrow-e', 'headerSelected': 'ui-icon-circle-arrow-s'}});});
				  function loadVarMan(loadingString) {
					  $('body',window.parent.frames[2].document).html("Loading Content...");
					  $('body',window.parent.frames[2].document).load('endpoints/advanced/varMan.php',{},
											  function() {
												  $('#varForm').ajaxForm({
																		 success: function(response) {
																							 $('body',window.parent.frames[2].document).html(response);
																							 }
																			})});
				  }
                </script>
            </body>
		  </html><?php
	} elseif ($_GET['frame'] == "header") {
		//Header Frame
		?><html>
        	<head><link href="style-header.css" rel="stylesheet" type="text/css" /></head>
            <body>
            <h1>ADMINISTRATOR CONTROL PANEL</h1>
            <p class="version">Latest Version Available: <?php echo file_get_contents("http://flumpshop.googlecode.com/svn/updater/version.txt");?></p>
            <div class="right">
            	<h1>flump<span class="header2">shop</span> <?php echo $config->getNode("site","version");?></h1>
                <p><a href='../account/logout.php'>Logout</a> | <a href='../'>View live storefront</a></p>
            </div>
            </body>
		  </html><?php
	} elseif ($_GET['frame'] == "main") {
		//Main Frame
		?><html>
        	<head><link href="style-main.css" rel="stylesheet" type="text/css" /></head>
            <body>
            <h1>Flumpshop Admin CP</h1>
            <p>PHP v<?php echo PHP_VERSION;?></p>
            <p>Database v<?php echo $dbConn->version();?>
            </body>
		  </html><?php
	}
} else {
	?><html><head><title>Flumpshop | Admin CP</title></head>
    	<frameset cols="252px,*" framespacing="0" border="0" frameborder="0" frameborder="no" border="0">
    	<frame name="leftFrame" id="leftFrame" src="?frame=leftFrame" scrolling="yes" frameborder="0" marginwidth="0" marginheight="0" border="no" />
        <frameset rows="60px,*" framespacing="0" border="0" frameborder="0" frameborder="no" border="0">
			<frame src="?frame=header" name="header" id="header" scrolling="no" noresize="noresize" frameborder="0" marginwidth="10" marginheight="0" border="no" />
			<frame src="?frame=main" name="main" id="main" scrolling="yes" frameborder="0" marginwidth="10" marginheight="10" border="no" />
		</frameset>
      </frameset>
	  </html><?php
}
?>