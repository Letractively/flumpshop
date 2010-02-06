<?php
require_once dirname(__FILE__)."/../header.inc.php";
?><h1>Database</h1>
<form action="../process/doDatabase.php" method="post" id="form"><table>
<tr>
	<td><label for="type">Engine</label></td>
    <td><select name='type' id="type" onchange='if ($(this).val() == "mysql") {$(".mysqli").show(); $("#address").val("localhost");} else {$(".mysqli").hide(); $("#address").val("<?php echo $_SESSION['config']->getNode('paths','offlineDir')?>/db.sqlite");}'><?php
    if ($_SESSION['additions']['mysqli']) echo "<option value='mysql'>MySQL</option>";
	if ($_SESSION['additions']['sqlite']) echo "<option value='sqlite'>SQLite</option>";
	if (!$_SESSION['additions']['mysqli'] && !$_SESSION['additions']['sqlite']) echo "<option>No Engines Available</option>";
	?></select></td>
    <td><span class='iconbutton' onclick='$("#typeHelp").dialog("open");'></span></td>
</tr>
<tr>
	<td><label for="address">Address/Path</label></td>
    <td><input type="text" name="address" id="address" value="<?php echo $_SESSION['config']->getNode('database','address');?>" /></td>
    <td><span class='iconbutton' onclick='$("#addressHelp").dialog("open");'></span></td>
</tr>
<tr class='mysqli'>
	<td><label for="port">Port</label></td>
    <td><input type="text" name="port" id="port" value="<?php echo $_SESSION['config']->getNode('database','port');?>" /></td>
    <td><span class='iconbutton' onclick='$("#portHelp").dialog("open");'></span></td>
</tr>
<tr class='mysqli'>
	<td><label for="uname">Username</label></td>
    <td><input type="text" name="uname" id="uname" /></td>
    <td><span class='iconbutton' onclick='$("#unameHelp").dialog("open");'></span></td>
</tr>
<tr class='mysqli'>
	<td><label for="password">Password</label></td>
    <td><input type="password" name="password" id="password" /></td>
    <td><span class='iconbutton' onclick='$("#passwordHelp").dialog("open");'></span></td>
</tr>
<tr class='mysqli'>
	<td><label for="name">Database</label></td>
    <td><input type="text" name="name" id="name" /></td>
    <td><span class='iconbutton' onclick='$("#nameHelp").dialog("open");'></span></td>
</tr>
</table>
<button onclick='saveSetup(); this.disabled = true; $(this).html("Processing...")' id='continue'>Continue</button>
</form>
<div id="status" class="ui-state-highlight"></div>

<div class="ui-helper-hidden helpDialog" id="typeHelp" title="Engine">This is the database engine that Flumpshop will use. If "No Engines Available" is displayed, then you must install the MySQLi or SQLite extension and restart setup to continue.</div>
<div class="ui-helper-hidden helpDialog" id="addressHelp" title="Address/Path">For MySQL databases, this is the address of the MySQL Server. For SQLite, this is the the path to the database file, including the file name.</div>
<div class="ui-helper-hidden helpDialog" id="portHelp" title="Port">The port used to connect to the MySQL Server.</div>
<div class="ui-helper-hidden helpDialog" id="unameHelp" title="Username">The username used to connect to the MySQL Server.</div>
<div class="ui-helper-hidden helpDialog" id="passwordHelp" title="Password">The password used to connect to the MySQL Server.</div>
<div class="ui-helper-hidden helpDialog" id="nameHelp" title="Database">The name of the database that Flumpshop will use. It must already have been created before you continue.</div>
<script>
document.logDirFocus = true;

function saveSetup() {
	$('#form').ajaxSubmit({timeout: 100000000});
	$('#status').html('Please Wait...');
	update();
	document.aborted = false;
}
function update() {
	if ($('#status').html() == "Finished!" && !document.aborted) {
		window.location = "about.php";
		document.aborted = true;
	} else if ($('#status').html() == "Database Connection Failed!" && !document.aborted) {
		$('#continue').removeAttr('disabled').html('Retry');
		document.aborted = true;
	} else {
		$('#status').load('../process/status.txt');
		setTimeout("update();",100);
	}
}
</script><?php
require_once dirname(__FILE__)."/../footer.inc.php";?>