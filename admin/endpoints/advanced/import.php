<?php require_once dirname(__FILE__)."/../header.php";?>
<div class="ui-widget-header">Import</div>
<p>This feature uses a previously exported copy of my database to fix altered or missing data. However, I'll write over any conflicting data I find whilst updating the database.</p>
<form action="./endpoints/process/doImport.php" method="post" enctype="multipart/form-data" onsubmit="$(this).ajaxSubmit({target: '#adminContent'}); $('#submitButton').attr('disabled',true).addClass('ui-state-disabled').val('Importing Data. This may take some time.'); return false;">
	<input type="file" name="file" id="file" /><br />
    <input type="checkbox" name="includeConf" id="includeConf" style="font-size: 12px; padding: .2em .4em;" />
    <label for='includeConf'>Replace the Configuration Object</label><br /><br />
    <input type="checkbox" name="includeImages" id="includeImages" style="font-size: 12px; padding: .2em .4em;" />
    <label for='includeImages'>Import images</label><br /><br />
    <input type="submit" id="submitButton" value="Import" class="ui-widget-content" />
    <div class="ui-state-highlight"><span class="ui-icon ui-icon-lightbulb"></span>If you haven't already noticed, the boxes above are actually checkboxes. Use them to toggle the replacement of certain items. Default: All Disabled</div>
</form>
<script type="text/javascript">
$('#includeConf, #includeImages').button();
</script>
</body></html>