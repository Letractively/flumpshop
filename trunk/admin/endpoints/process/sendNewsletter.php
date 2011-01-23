<?php
$USR_REQUIREMENT = "can_contact_customers";

require_once "../header.php";
//Takes the preview snapshot stored in $_SESSION and emails it to everyone.

/*
$_SESSION['newsletter_title'] contains the subject
$_SESSION['newsletter_cache'] contains the body
*/

//Store the newsletter in the database for online viewing and archiving
$dbConn->query("INSERT INTO `newsletters` (title,body) VALUES ('".htmlentities($_SESSION['newsletter_title'],ENT_QUOTES)."','".htmlentities($_SESSION['newsletter_cache'],ENT_QUOTES)."')");
//Get the id
$mailingid = $dbConn->insert_id();

//Update the mailer so the ID is replaced
$dbConn->query('UPDATE `newsletters`
	SET body="'.str_replace(array('"', '[[[mailing_id]]]'),array('""', strval($mailingid)),$_SESSION['newsletter_cache']).'"
	WHERE newsletter_id='.$mailingid.' LIMIT 1');

//Add the newsletter sends to the queue
$dbConn->query('INSERT INTO newsletter_queue (name,email,newsletter_id) SELECT name,email, "'.$mailingid.'" FROM `customers` WHERE archive=0 AND can_contact=1');
$_SESSION['messageTotal'] = $dbConn->affected_rows();


//Count the number of emails sent
$_SESSION['messageCounter'] = 0;
?><h1>Sending Newsletter</h1>
<p>Flumpshop has now added the newsletter to its archives and is distributing it as we speak.</p>
<p>For fastest sending times, keep this window open as it will send emails more quickly.</p>
<div id="progress">Flumpshop is sending the first batch of emails. This newsletter will be sent to a total of <?php echo $_SESSION['messageTotal'];?> recipients.</div>
<script type="text/javascript">
function processEmails() {
	$('#progress').load('../advanced/processMailQueue.php?time=10',function(){processEmails()});
}
setTimeout("processEmails();",200);
</script>