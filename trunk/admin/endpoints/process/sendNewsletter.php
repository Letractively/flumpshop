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
//Place the mailerid into the email
$_SESSION['newsletter_cache'] = str_replace('[[[mailing_id]]]',$mailingid,$_SESSION['newsletter_cache']);

//Load the SMTP connection
$mailer = new Mail();

//Get a list of non-archived customers with contact enabled
$result = $dbConn->query('SELECT name,email FROM `customers` WHERE archive=0 AND can_contact=1');

//Count the number of emails sent
$sent = 0;
//Keep track of sent addresses
$used_addresses = array();
//Send the email to each of these customers
while ($row = $result->fetch_array()) {
	if (!array_search($row['email'],$used_addresses)) {
		//Prevents duplicate emails
		$used_addresses[] = $row['email'];
		$mailer->send($row['name'], $row['email'], $_SESSION['newsletter_title'], $_SESSION['newsletter_cache']);
		$sent++;
	}
}
?><h1>Sent</h1>
<p>Your newsletter has been sent to <?php echo $sent;?> email addresses.</p>