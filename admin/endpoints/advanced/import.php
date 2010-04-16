<?php
$requires_tier2 = true;
require_once dirname(__FILE__)."/../header.php";?>
<div class="ui-widget-header">Import</div>
<p>This feature uses a previously exported copy of my database to fix altered or missing data. However, I'll write over any conflicting data I find whilst updating the database.</p>
<form action="../process/doImport.php" method="post" enctype="multipart/form-data" onsubmit="$(this).ajaxSubmit({target: '#adminContent'}); $('#submitButton').attr('disabled',true).addClass('ui-state-disabled').val('Importing Data. This may take some time.'); return false;">
	<input type="file" name="file" id="file" /><br />
    <input type="checkbox" name="includeConf" id="includeConf" style="font-size: 12px; padding: .2em .4em;" />
    <label for='includeConf'>Replace the Configuration Object</label><br /><br />
    <input type="checkbox" name="includeImages" id="includeImages" style="font-size: 12px; padding: .2em .4em;" />
    <label for='includeImages'>Import images</label><br /><br />
    <input type="submit" id="submitButton" value="Import" class="ui-widget-content" />
</form>
<h1>Import 2<sup>labs</sup></h1>
<p>We're currently working on recreating the import/export feature to use XML data. This offers reduced filesize, as well as other benefits.</p>
<form action="../data/doImport.php" method="post" enctype="multipart/form-data">
	<input type="file" name="data" id="data" />
	<div onclick="$('#more').toggle();">Options</div>
	<div id="options" style="display:none;">
	<input type="checkbox" name="acpusers" id="aspusers" />Import ACP Users<br />
	<input type="checkbox" name="baskets" id="baskets" />Import Baskets<br />
	<input type="checkbox" name="bugs" id="bugs" />Import Bugs<br />
	<input type="checkbox" name="categories" id="categories" />Import Categories<br />
	<input type="checkbox" name="acpusers" id="aspusers" />Import ACP Users<br />
	<input type="checkbox" name="acpusers" id="aspusers" />Import ACP Users<br />
	<input type="checkbox" name="acpusers" id="aspusers" />Import ACP Users<br />
	<input type="checkbox" name="acpusers" id="aspusers" />Import ACP Users<br />
	<input type="checkbox" name="acpusers" id="aspusers" />Import ACP Users<br />
	<input type="checkbox" name="acpusers" id="aspusers" />Import ACP Users<br />
	<input type="checkbox" name="acpusers" id="aspusers" />Import ACP Users<br />
	<input type="checkbox" name="acpusers" id="aspusers" />Import ACP Users<br />
	<input type="checkbox" name="acpusers" id="aspusers" />Import ACP Users<br />
	<input type="checkbox" name="acpusers" id="aspusers" />Import ACP Users<br />
	<input type="checkbox" name="acpusers" id="aspusers" />Import ACP Users<br />
	<input type="checkbox" name="acpusers" id="aspusers" />Import ACP Users<br />
	<input type="checkbox" name="acpusers" id="aspusers" />Import ACP Users<br />
	<input type="checkbox" name="acpusers" id="aspusers" />Import ACP Users<br />
	<input type="checkbox" name="acpusers" id="aspusers" />Import ACP Users<br />
	<input type="checkbox" name="acpusers" id="aspusers" />Import ACP Users<br />
	<input type="checkbox" name="acpusers" id="aspusers" />Import ACP Users<br />
	<input type="checkbox" name="acpusers" id="aspusers" />Import ACP Users<br />
	<input type="checkbox" name="acpusers" id="aspusers" />Import ACP Users<br />
	<input type="checkbox" name="acpusers" id="aspusers" />Import ACP Users<br />
	</div>
	<input type="submit" value="Import" />
</form>
</body></html>