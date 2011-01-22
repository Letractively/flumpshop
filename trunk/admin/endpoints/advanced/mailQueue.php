<?php
$requires_tier2 = true;
require_once "../header.php";
$result = $dbConn->query('SELECT COUNT(*) FROM newsletter_queue');
$row = $dbConn->fetch($result);
$rows = $row[0];
$_SESSION['messageTotal'] = $rows;
$_SESSION['messageCounter'] = 0;
?><h1>Mail Queue</h1>
<p>Newsletters sent by Flumpshop are sent gradually over a period of time in the background when a page is loaded. This is so that if you close the page you sent the newsletter with, they will still gradually be deployed. Additionally, it prevents the SMTP server from being overloaded by requests.</p>
<p>The Flumpshop mail queue currently has <?php echo $rows;?> items waiting to be delivered. You can use the form below to process a batch of emails immediately.</p>
Start processing the queue in <input type="text" class="required number" id="process_time" value="30" /> second intervals.
<a href="javascript:" onclick="startQueue();">Start Running</a>
<div id="mailerresult"></div>
<script type="text/javascript">
function startQueue() {
	$('#mailerresult').html('Flumpshop is now processing emails in batches of '+$('#process_time').val()+' seconds.');
	continueQueue();
}

function continueQueue() {
	$('#mailerresult').load('../advanced/processMailQueue.php?time='+$('#process_time').val(),
	function(){continueQueue()});
}
</script>
</body></html>