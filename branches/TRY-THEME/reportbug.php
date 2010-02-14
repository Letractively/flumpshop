<?php require_once dirname(__FILE__)."/preload.php"; $page_title = "Send Feedback"; require_once dirname(__FILE__)."/header.php";?>
  <h1 class="content">Send Feedback</h1>
  <?php if (isset($_GET['posted'])) echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-circle-check'></span>Thank you. Your feedback has been sent.</div>";?>
    <p>Hello, and thanks for trying out RJC Commercial Catering's new website. If you're having a problem with the site, please fill out the form below and we'll get it fixed as soon as possible. And if you want to tell us that everything's working brilliantly, then we won't say no to that either.</p>
    <form action="saveBug.php" method="post" name="reporter" id="reporter">
      <table>
        <tr><td><label for="header">Title: </label></td><td><input type="text" name="bugHeader" id="bugHeader" class="ui-state-default required" minlength='10' /></td></tr>
        <tr><td>&nbsp;</td><td><textarea name="body" id="body" class="ui-state-default required" style="width: 500px; height: 150px;" minlength='30'></textarea></td></tr>
        <tr><td>&nbsp;</td><td><input type="submit" value="Send" class="ui-state-default" /></td></tr>
      </table>
    </form>
    <script>$('#reporter').validate();</script>
<?php
require_once dirname(__FILE__)."/footer.php";
?>