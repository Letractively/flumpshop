<?php
require_once "../header.php";

if (isset($_GET['do'])) {
	//The form has been submitted
	$email = htmlentities($_POST['email'],ENT_QUOTES);
	//Disable can_contact for active customer with this email
	$dbConn->query("UPDATE `customers` SET can_contact=0 WHERE email='$email' AND archive=0 LIMIT 1");
	echo $email." has been removed from the mailing list.<br /><br />";
}
?><h1>Unsubscribe</h1>
<p>If you no longer wish to receive newsletters from <?php echo $config->getNode("messages","name");?>, enter your email address below to be removed from our mailing list.</p><br />
<form action="unsubscribe.php?do" method="post" id="unsubform">
<input type="text" name="email" id="email" class="ui-state-default required email" />
<input type="submit" value="Unsubscribe" class="ui-state-default" />
</form>
<script type="text/javascript">
$('#unsubform').validate();
</script><?php
require_once "../footer.php";
?>