<?php
$ajaxProvider = true;
require_once dirname(__FILE__)."/../../includes/preload.php";
acpusr_validate();
$msg = 'Change your password regularly to keep your account safe';
if (isset($_POST['oldpass'])) {
	//Form already sent, change the password
	if ($_POST['newpass1'] != $_POST['newpass2']) {
		$msg = 'The new passwords did NOT match.';
	} else {
		$dbConn->query('UPDATE acp_login SET pass="'.md5(sha1($_POST['newpass1'])).'", pass_expires="'.$dbConn->time(time()+3600*24*30).'" WHERE uname="'.$acp_uname.'" LIMIT 1');
		header('Location: ./');
		exit;
	}
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"
><head>
<style type="text/css">
body {background-color: #1e2b5b; color: #FFF; font: Arial, Helvetica, sans-serif;}
form {width: 300px; margin: 150px auto 0 auto;}
.header {font: "Trebuchet MS", Arial, Helvetica, sans-serif; font-size: 23px;}
.header2 {color: #c43131;}
.title {background-color: #0e78ee; padding: 0 5px; line-height: 1.5; font-size: 17px;}
.content {background-color: #e7e7e7; padding: 0 5px; color: #000; font-size: 12px;}
.content label {color: #1e2b5b;}
table td {text-align: right;}
input {border: 1px solid #1e2b5b; color: #1e2b5b; width: 180px;}
input.submit {width: auto; position: relative; left: 140px; border: 3px outset #1e2b5b; background: #FFF; color: #1e2b5b; font-size: 14px; font-weight: bold;}
#skipImg{float:right;padding-top:5px}
</style>
<title>Change Password | Flumpshop</title></head><body>
<form action="./change_password.php" method="post">
<div class="header"><img src="images/logo.jpg" alt="Flumpshop Logo" />flump<span class='header2'>shop</span></div>
<div class="title">password expired...<a href="./"><img id="skipImg" src="images/pwclose.png" title="Change password later" /></a></div>
<div class="content">
<?php echo $msg;?><table>
<tr><td><label for='oldpass'>Old Password: </label></td><td><input type="password" name="oldpass" id="oldpass" /></td></tr>
<tr><td><label for='newpass1'>New Password: </label></td><td><input type="password" name="newpass1" id="newpass1" /></td></tr>
<tr><td><label for='newpass2'>Confirm Password: </label></td><td><input type="password" name="newpass2" id="newpass2" /></td></tr>
</table>
<input type="submit" class="submit" value="Change Password" />
</div>
</form>
</body></html>