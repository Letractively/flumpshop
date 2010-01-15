<?php header("HTTP/1.1 500 Internal Server Error");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>500 Internal Server Error</title>
<link rel='stylesheet' href='../style/style.css' type='text/css' />
<link rel='stylesheet' href='../style/style-subpage.css' type='text/css' />
<link rel='stylesheet' href='../style/jquery.css' type='text/css' />
<link rel='stylesheet' href='../style/jquery-overrides.css' type='text/css' />
</head>
<body>
<div id='header'>
<div id="title">
<h1>Oops!</h1>
<h1 class='drop_shadow'>Oops!</h1>
<h1 class='slogan'>I didn't do it...</h1>
</div>
</div>
<div id='content_container_background'>
<div id="content_container">
<div id="mainContent">
<h1 class="content">I Can't Do That Right Now</h1>
Sorry, I've tried as hard as I can, but I just can't manage to process your request. This is a Fatal Error, and I've given up for now, but you can try again in a few minutes. If you keep getting this message, please let my Webmaster know.<br /><br />

<div class="ui-state-error"><span class="ui-icon ui-icon-alert"></span><?php echo base64_decode($_GET['err']); ?></div><br />
We apologise for the inconvenience caused.<br />
System Administrator: Click <a href="../admin/setup/index.php"> here</a> to force system reconfiguration.

</div>
</div>
<div id='footer'>A 500 Internal Server Error was encountered processing your request.</div></div>
</body>
</html>