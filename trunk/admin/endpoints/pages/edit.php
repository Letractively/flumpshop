<?php
$USR_REQUIREMENT = "can_edit_pages";
require_once dirname(__FILE__)."/../header.php";

$content = $_GET['pageid'];

if (isset($_POST['content'])) {
	$config->setNode('messages',$content,trim(str_replace(array("\\","\n"),
													 array("","<br />"),
													 $_POST['content'])));
	$msg = "<div class='ui-state-highlight'><span class='ui-icon ui-icon-circle-check'></span>Page Saved</div>";
	header("Location: ../switchboard/pages.php?msg=".$msg);
}?>
<script type="text/javascript" src="../../tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript">
	$().ready(function() {
		$('#content').tinymce({
			// Location of TinyMCE script
			script_url : '../../tiny_mce/tiny_mce.js',

			// General options
			theme : "advanced",
			plugins : "pagebreak,style,layer,table,advlink,iespell,inlinepopups,preview,media,searchreplace,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,advlist",

			// Theme options
			theme_advanced_buttons1 : "bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,forecolor",
			theme_advanced_buttons2 : "search,|,bullist,numlist,|,blockquote,|,link,unlink,code,|,preview,|",
			theme_advanced_buttons3 : "hr,removeformat,|,sub,sup,|,charmap,iespell,media,cleanup",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,

			// Example content CSS (should be your site CSS)
			content_css : "../../../style/cssProvider.php?theme=<?php echo $config->getNode("site","theme");?>&sub=main",

			// Drop lists for link/image/media/template dialogs
			template_external_list_url : "lists/template_list.js",
			external_link_list_url : "lists/link_list.js",
			media_external_list_url : "lists/media_list.js"
		});
	});
</script>
<h1>Edit <?php echo $config->getFriendName('messages',$content);?></h1>
<p>Enter the page content below. Advanced users: you can insert predefined messages by using [[field name]] in the message, e.g. [[address]] will include the address field.</p>
<form action='edit.php?pageid=<?php echo $content;?>' method='POST' onsubmit='if ($(this).valid()) {loader(loadMsg("Saving Content...")); return true;} else return false;'>
    <textarea name="content" id="content" class="ui-state-default required" style="width: 99%; height: 250px;">
    <?php echo $config->getNode('messages',$content); ?>
    </textarea>
    <input type="submit" class="ui-state-default" value="Save" style="font-size: 12px; padding: .3em .4em;" />
</form></body></html>