This feature uses a previously exported copy of my database to fix altered or missing data. However, I'll write over any conflicting data I find whilst updating the database.
<form action="./endpoints/process/doImport.php" method="post" enctype="multipart/form-data" onsubmit="$(this).ajaxSubmit({target: '#adminContent'}); $('#submitButton').attr('disabled',true).addClass('ui-state-disabled').val('Importing Data. This may take some time.'); return false;">
	<input type="file" name="file" id="file" /><br />
    <input type="checkbox" name="includeConf" id="includeConf" checked="checked" />
    <label for='includeConf'>Replace the Configuration Object</label><br />
    <input type="checkbox" name="includeImages" id="includeImages" checked="checked" />
    <label for='includeImages'>Import images</label><br />
    <input type="submit" id="submitButton" value="Import" class="ui-widget-content" />
</form>