<?php require_once dirname(__FILE__)."/preload.php"; $page_title = "Send Feedback"; require_once dirname(__FILE__)."/header.php";?>
  <h1 class="content">Send Feedback</h1>
  <?php if (isset($_GET['posted'])) echo "<div><span class='ui-icon ui-icon-circle-check'></span>Thank you. Your feedback has been sent.</div>";?>
    <form action="saveBug.php" method="post" name="reporter" id="reporter">
      <table>
        <tr><td><label for="header">Subject: </label></td><td><input type="text" name="bugHeader" id="bugHeader" class="required" minlength='2' /></td></tr>
		<tr><td><label for="email">Email (optional): </label></td><td><input type="text" name="email" id="email" class="email" /></td></tr>
        <tr><td>&nbsp;</td><td><textarea name="body" id="body" class="required" style="width: 500px; height: 150px;" minlength='5'></textarea></td></tr>
        <tr><td>&nbsp;</td><td><input type="submit" value="Send" /></td></tr>
      </table>
    </form>
    <script>$('#reporter').validate();</script><?php

require_once dirname(__FILE__)."/footer.php";
?>