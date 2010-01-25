<?php
$ajaxProvider = true;
require_once dirname(__FILE__)."/../../../preload.php";
if (!isset($_SESSION['adminAuth']) || !$_SESSION['adminAuth']) $config->getNode('messages','adminDenied');
?>
<form action="./endpoints/process/insertTechhelp.php" method="post" onsubmit="if ($(this).valid()) {$(this).ajaxSubmit({target: '#adminContent'});} return false;">
	<fieldset class="ui-widget">
    	<label for="postTitle">Title: </label>
        <input type="text" name="postTitle" id="postTitle" class="required ui-widget-content" maxlength="250" style="width: 500px;" /><br />
        <textarea name="postContent" id="postContent" class="required ui-widget-content" style="width: 90%; height: 200px;"></textarea><br />
        <input type="submit" value="Add Technical Help Post" class="ui-widget-content" />
    </fieldset>
</form>