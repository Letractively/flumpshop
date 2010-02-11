<?php
require_once dirname(__FILE__)."/../header.php";

$content = $_GET['pageid'];

if (isset($_POST['content'])) {
	$config->setNode('messages',$content,trim(str_replace(array("\\","\n"),
													 array("","<br />"),
													 $_POST['content'])));
	echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-circle-check'></span>Page Saved</div>";
}?><form action='./editPageContent.php?pageid=<?php echo $content;?>' method='POST' onsubmit='if ($(this).valid()) {loader(loadMsg("Saving Content...")); return true;} else return false;'>
    <textarea name="content" id="content" class="ui-state-default required" style="width: 99%; height: 250px;">
    <?php echo trim(str_replace("<br />", "\n",$config->getNode('messages',$content))); ?>
    </textarea>
    <input type="submit" class="ui-state-default" value="Save" style="font-size: 12px; padding: .3em .4em;" />
</form></body></html>