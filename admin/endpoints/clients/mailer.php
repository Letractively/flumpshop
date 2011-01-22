<?php
require_once "../header.php";
?><h1>Client Mailer</h1>
<p>If you have configured an SMTP server, you can use this page to send a message to all users who have given permission for you to contact them about special offers.</p>
<p>As part of this process, I, the Flumpnet robot, will automatically build an email based on your website theme. Alternatively, you can manually edit the source code to use a special theme just for emails.</p>
<form action="mailerPreview.php" method="post" target="preview">
<label for="title">Subject: </label><input type="text" name="title" id="title" /><br />
<textarea name="email" id="email">Type your email here, then click Preview to view it below.</textarea>
<input type="submit" value="Preview" onclick="$('#sendlink').show(); return true;" />
</form>
Preview:<br />
<iframe src="./mailerPreview.php" id="preview" style="width: 800px; height: 800px;"></iframe>
<br /><a href="../process/sendNewsletter.php">Send Newsletter!</a>
<script type="text/javascript"></script> 
</body></html>