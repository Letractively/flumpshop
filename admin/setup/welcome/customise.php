<?php require_once dirname(__FILE__)."/../header.inc.php";
?><h1>Customise Setup</h1>
<p>In order to streamline your setup experience, I would like to know how much detail you wish to go in at this time. You can choose one of the preconfigured options, or set a custom setup.</p>
<p>All modes include these steps: </p>
<ol>
    <li>Paths and Directories</li>
    <li>Database Configuration</li>
    <li>About You</li>
</ol>
<div id="content">
Method:
<h3>Express</h3>
<div>
	<p>This mode only asks for the mission-critical information, for the fastest and simplest setup experience. This stage should only take a few minutes to complete.</p>
    <ol start="4">
    	<li>Express mode has no additional configuration settings</li>
    </ol>
    <button onclick='window.location = "doConfigure.php?mode=express";'>Choose Express</button>
</div>
<h3>Complete</h3>
<div>
	<p>This mode gives you complete control over your Flumpshop configuration, comprehensively allowing you to change each and every setting available. Allow yourself up to an hour to complete this.</p>
    <ol start="4">
    	<li>Security Settings</li>
        <li>Shop Settings</li>
        <li>PayPal Settings</li>
        <li>Predefined Messages</li>
        <li>Pagination Settings</li>
        <li>User Account Settings</li>
        <li>SMTP Server Settings</li>
        <li>Log Settings</li>
        <li>Advanced Server Settings</li>
        <li>Tab Settings</li>
        <li>Home Page Settings</li>
        <li>Item View Settings</li>
		<li>Delivery Settings</li>
    </ol>
    <button onclick='window.location = "doConfigure.php?mode=complete";'>Choose Complete</button>
</div>
<h3>Tailored</h3>
<div>
	<p>This mode automatically chooses the most complete settings based on the analysis performed on the last page. If you have no additions support, then this will be identical to Express mode.</p>
	<ol start="4"><?php
	$query = "";
	if ($_SESSION['additions']['curl']) {
		echo "<li>PayPal Settings</li>";
		$query .= "&paypal=true";
	}
	?></ol>
    <button onclick='window.location = "doConfigure.php?mode=tailored<?php echo $query;?>";'>Choose Tailored</button>
</div>
<h3>Custom</h3>
<div>
	<p>If you know exactly what you want, then use this to cut out the parts you don't want to change.</p>
    <form action='doConfigure.php?mode=custom' method="post" id="custom" name="custom">
    <ol start="4">
    	<li><input type="checkbox" name="security" id="security" /><label for="security">Security Settings</label></li>
        <li><input type="checkbox" name="shop" id="shop" /><label for="shop">Shop Settings</label></li>
        <li><input type="checkbox" name="paypal" id="paypal" /><label for="paypal">PayPal Settings</label></li>
        <li><input type="checkbox" name="messages" id="messages" /><label for="messages">Predefined Messages</label></li>
        <li><input type="checkbox" name="pagination" id="pagination" /><label for="pagination">Pagination Settings</label></li>
        <li><input type="checkbox" name="account" id="account" /><label for="account">User Account Settings</label></li>
        <li><input type="checkbox" name="smtp" id="smtp" /><label for="smtp">SMTP Server Settings</label></li>
        <li><input type="checkbox" name="logs" id="logs" /><label for="logs">Log Settings</label></li>
        <li><input type="checkbox" name="server" id="server" /><label for="server">Advanced Server Settings</label></li>
        <li><input type="checkbox" name="tabs" id="tabs" /><label for="tabs">Tab Settings</label></li>
        <li><input type="checkbox" name="homePage" id="homePage" /><label for="homePage">Home Page Settings</label></li>
        <li><input type="checkbox" name="viewItem" id="viewItem" /><label for="viewItem">Item View Settings</label></li>
		<li><input type="checkbox" name="delivery" id="delivery" /><label for="delivery">Delivery Settings</label></li>
    </ol>
    <button onclick="$('#custom').submit();">Choose Custom</button>
    </form>
</div>
</div>
<script type="text/javascript">
$('#content').accordion({icons: false, collapsible: true, autoHeight: false});
$('button, input:submit').button();
</script>