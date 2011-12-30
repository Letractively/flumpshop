<?php

require_once dirname(__FILE__)."/../../../includes/vars.inc.php";

//Process Login, if submitted
if (isset($_POST['password'])) {
	if (sha1($_POST['password']) == $config->getNode("site","password")) {
		$_SESSION['adminAuth'] = true;
		header("Location: ./");
		die();
	}
}

/**
 * Not logged in, load login form
 */
$login_message = 'Please enter the second-tier password to continue';
$login_action = 'login.php';
$login_fields = array(
    array(
        'label' => 'Password: ',
        'type' => 'password',
        'id' => 'password'
    )
);

require dirname(__FILE__) . '/../../../views/admin_login.inc';
