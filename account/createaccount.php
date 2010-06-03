<?php
$page_title = "Sign Up";
define("PAGE_TYPE", "createAccount");
require_once dirname(__FILE__)."/../header.php";

ob_start();

extract($_POST);
if (isset($contact)) $contact = 1; else $contact = 0;

//Server Side Validation
function print_err($str) {
	echo "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>$str</div>";
}
$error = false;
//username
if (strlen($username) < 5 or strlen($username) > 75 or preg_match("/[^a-zA-Z0-9_-\s]/",$username) or $dbConn->rows($dbConn->query("SELECT id FROM `login` WHERE uname='".str_replace("'","''",$username)."' LIMIT 1")) == 1) {
	print_err("Invalid Username");
	$error = true;
}
//password
if ($pass1 != $pass2 or strlen($pass1) < 6) {
	print_err("Invalid Password");
	$error = true;
}
//email
if (empty($email) && $config->getNode("account","requireEmailValidation")) {
	print_err("Invalid Email");
	$error = true;
}
if (!empty($email)) {
	$doEmail = true;
} else {
	$doEmail = false;
}
//ADDRESS
if ($config->getNode('account','requireAllAtSignup')) {
	$address = true;
	if (empty($name) or empty($address1) or empty($address2) or empty($address3) or empty($postcode) or empty($country)) {
		print_err("Invalid Address.");
		$error = true;
	}
} else {
	//Check if address has been entered
	if (!empty($name) and !empty($address1) and !empty($address2) and !empty($address3) and !empty($postcode) and !empty($country)) {
		$address = true;
	} else {
		$address = false;
	}
}

if ($error) {
	echo "Sorry. I can't sign you up until you fix the problems listed above.";
} else {
	//Create Account
	if ($config->getNode('account','requireEmailValidation')) $active = 0; else $active = 1;
	$user = new User();
	$user->populate($username,md5($pass1),0,$active);
	if ($address and $doEmail) {
		$customer = new Customer();
		$customer->populate($name,$address1,$address2,$address3,$postcode,$country,$email,NULL,$contact);
		$user->replaceCustomerObj($customer);
	} elseif ($doEmail and !$address) {
		$customer = new Customer();
		$customer->populate(NULL,NULL,NULL,NULL,NULL,NULL,$email,NULL,$contact);
		$user->replaceCustomerObj($customer);
	}
	//{} Not Supported in PHP 4
	$name = $config->getNode("messages","name");
	$root = $config->getNode('paths','root');
	//Account Created. Send email
	if ($doEmail) {
		$mailer = new Mail();
		if ($active == 0) {
			//Validation Email
			$hrs = $config->getNode("account","validationTimeout");
			$code = md5(time().session_id().rand(0,10000));
			
			$dbConn->query("INSERT INTO `keys` (`key`,expiry) VALUES ('$code','".$dbConn->time(time()+(3600*$hrs))."')");
			$key_id = $dbConn->insert_id();
			$dbConn->query("INSERT INTO `keys_action` (key_id,action) VALUES ($key_id,'ActivateAccount_".$user->getID()."')");
			$dbConn->query("INSERT INTO `keys_expiry` (key_id,action) VALUES ($key_id,'DeleteAccount_".$user->getID()."')");
			
			$mailer->send($user->getUname(),$email,"Registration Confirmation",
			str_replace("__regLink__","<a href='$root/account/verify.php?key=$code'>$root/account/verify.php?key=$code</a>",
			placeholders($config->getNode("messages","confirmAccountEmail"))));
			
			echo <<<EOT
<h1 class="content">Sign Up</h1>
<p>Thank you for registering. Please check your emails to finish the registration process.</p>
EOT;
		} else {
			//No Validation Required. Confirmation Only.
			$mailer->send($name,$email,"Registration Confirmation",<<<EOT
<html><head><title>Registration Confirmation</title></head>
Hello $uname,<br /><br />
Thank you for registering with $root. It looks like this website was only set up recently, so I'm afraid that this is just a placeholder message. It might be a good idea to contact us so that we can create a nice, pretty registration email that's full of useful information.<br /><br />
The $name team
</body></html>
EOT
);
			echo <<<EOT
<h1 class="content">Sign Up</h1>
<p>Thank you for registering. Your registration has been completed, and you can now log in by clicking the login button above.</p>
EOT;
		}
	}//End doMailer
	else {
		//No email specified. Confirmation page only.
		echo <<<EOT
<h1 class="content">Sign Up</h1>
<p>Thank you for registering. Your registration has been completed, and you can now log in by clicking the login button above.</p>
EOT;
	}
}

templateContent();

require_once dirname(__FILE__)."/../footer.php";
?>