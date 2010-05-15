<?php
require_once "preload.php";
define("PAGE_TYPE","feedback");
$page_title = $config->getNode("messages","sendFeedbackHeader");

require_once dirname(__FILE__)."/header.php";
ob_start();

?><h1 class="content"><?php echo $config->getNode("messages","sendFeedbackHeader");?></h1>
<?php if (isset($_GET['posted'])) echo "<div><span class='ui-icon ui-icon-circle-check'></span>Thank you. Your feedback has been sent.</div>";?>
    <form action="saveBug.php" method="post" name="reporter" id="reporter">
      <table>
        <tr><td><label for="header">Subject: </label></td><td><input type="text" name="bugHeader" id="bugHeader" class="required" minlength='2' /></td></tr>
		<tr><td><label for="email">Your Email: </label></td><td><input type="text" name="email" id="email" class="required email" /></td></tr>
        <tr><td>Message:</td><td><textarea name="body" id="body" class="required" style="width: 500px; height: 150px;" minlength='5'></textarea></td></tr>
        <tr><td>&nbsp;</td><td><input type="submit" value="Send" /></td></tr>
      </table>
    </form>
    <script>$('#reporter').validate();</script><?php

templateContent();

require_once dirname(__FILE__)."/footer.php";
?>