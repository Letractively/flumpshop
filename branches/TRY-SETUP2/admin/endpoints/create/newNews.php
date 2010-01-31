<?php
require_once dirname(__FILE__)."/../header.php";
?><div class="ui-widget-header">New News Post</div>
<form action="../process/insertNews.php" method="post" onsubmit="if ($(this).valid()) {$(body).html(loadMsg('Saving Content...')); return true;} else return false;" class="ui-widget-content">
<p>This post will appear on the home page, under the <?php echo $config->getNode("messages","latestNewsHeader");?> heading.</p>
    <label for="postTitle">Title: </label>
    <input type="text" name="postTitle" id="postTitle" class="required ui-widget-content" maxlength="250" style="width: 500px;" /><br />
    <textarea name="postContent" id="postContent" class="required ui-widget-content" style="width: 90%; height: 200px;"></textarea><br />
    <input type="submit" value="Add Post" class="ui-widget-content" style="font-size: 13px;" />
</form>