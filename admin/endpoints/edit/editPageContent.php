<?php
$ajaxProvider = true;
require_once dirname(__FILE__)."/../../../preload.php";

$content = $_GET['pageid'];

if (isset($_POST['content'])) {
	$config->setNode('messages',$content,str_replace("\\","",$_POST['content']));
	echo "Page saved.";
} else {
	?>
    <form action='./endpoints/edit/editPageContent.php?pageid=<?php echo $content;?>' method='POST' onsubmit='if ($(this).valid()) $(this).ajaxSubmit({target: "#adminContent"}); return false;'>
    	<textarea name="content" id="content" class="ui-state-default required" style="width: 99%; height: 250px;">
		<?php echo $config->getNode('messages',$content); ?>
        </textarea>
        <input type="submit" class="ui-state-default" value="Save!" />
    </form>
    <?php
}
?>