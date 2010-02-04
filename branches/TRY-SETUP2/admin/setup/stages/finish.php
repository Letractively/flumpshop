<?php
require_once dirname(__FILE__)."/../header.inc.php";
?><h1>Finish</h1>
<p>Well, we're reaching the end of our time together. Depending on what you selected, we may barely know each other, or it may seem like we have known each other for ever, or maybe we have.</p>
<p>Friendships aside, click the big button that says "Save" (yes, that one), to save all the things you've told me, and to start up your new site. Alternatively, it is possible to press F5 in your browser to make it like we never met.</p>
<p>Make your decision wisely, for I have all the time in the world. However, I should point out that from this point on I'm paid by the hour, and am also already late for my daughters xylophone recital. So come on, make a decision already. NOW. Come on, I haven't got all day.</p>
<button onclick='saveSetup(); this.disabled = true;'>Save</button>
<div id="status" class="ui-state-highlight"></div>
<script type="text/javascript">
function saveSetup() {
	$.ajax({url: "../process/doFinish.php", timeout: 1000000});
	setTimeout("update();",100);
}
function update() {
	$('#status').load('../process/status.txt');
	setTimeout("update();",100);
}
</script><?php
print_r($_SESSION['config']);
require_once dirname(__FILE__)."/../footer.inc.php";
?>