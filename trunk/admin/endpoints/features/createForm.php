<?php
$USR_REQUIREMENT = "can_add_features";
require_once dirname(__FILE__)."/../header.php";
//Outputs a simple form for creating features
?><h1>Add Feature</h1>
<p>This page allows you to define new features for the Flumpnet Robot to compare. Once it has been added, the robot will learn it, and add the attribute to the list available on any edit category page.</p>
<form action="../features/processCreateForm.php" method="post" id="form">
<table id="form_container">
	<tr>
		<td><label for="name">Attribute Name:</label></td>
		<td><input type="text" name="name" id="name" class="ui-state-default required text" /></td>
		<td>This is the name of the attribute, e.g. Colour</td>
	</tr>
	<tr>
		<td><label for="datatype">Data Type:</label></td>
		<td><select name="datatype" id="datatype" class="ui-state-default required" onchange="updateUnitsSub();">
				<option selected="selected" disabled="disabled">Please choose a data type...</option>
				<option value="number">Number</option>
				<option value="string">Text</option>
				<option value="date">Date</option>
			</select></td>
		<td>What type of information will this attribute store?</td>
	</tr>
	<tr>
		<td><label for="default">Default Value:</label></td>
		<td><input type="text" name="default" id="default" class="ui-state-default" /></td>
		<td>This is what is initially shown on the Create Item page when this attribute is loaded.</td>
	</tr>
	<tr>
		<td colspan="3" class="ui-widget-header">Units</td>
	</tr>
	<tr>
		<td id="unitsContainer" colspan="2">Units do not apply to your selection.</td>
		<td style="display:none;vertical-align:top">If you have selected a number, then you can set up units, e.g. Kg, MB. <a href="javascript:" onclick="addUnitsSub();">Add Another</a></td>
	</tr>
</table>
<input type="submit" value="Create" />
</form>
<script>
document.lastDataTypeValue = '';
document.nextUnitsFieldID = 0;
function updateUnitsSub() {
	if ($('#datatype').val() != document.lastDataTypeValue) {
		document.lastDataTypeValue = $('#datatype').val();
		if (document.lastDataTypeValue == "number") {
			$('#unitsContainer').load('../features/unitsField.php?id='+document.nextUnitsFieldID);
			document.nextUnitsFieldID++;
			$('#unitsContainer').next('td').show();
		} else {
			$('#unitsContainer').html('Units do not apply to your selection.');
			document.nextUnitsFieldID = 0;
			$('#unitsContainer').next('td').hide();
		}
	}
}

function addUnitsSub() {
	//TODO: Append instead (replaces atm, completely useless function)
	$.ajax({url:'../features/unitsField.php?id='+document.nextUnitsFieldID,success:function(data,status){$('#unitsContainer').append(data)}});
	document.nextUnitsFieldID++;
}
</script></body></html>