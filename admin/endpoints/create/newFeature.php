<?php
require_once "../header.php";
//Outputs a simple form for creating features
?><h1>Add Feature Attribute</h1>
<p>This page allows you to define new features for the Flumpnet Robot to compare. Once it has been added, the robot will learn it, and add the attribute to the list available on any edit category page.</p>
<form action="../process/insertFeature.php" method="post" id="form">
<table id="form_container">
	<tr>
		<td><label for="name">Attribute Name:</label></td>
		<td><input type="text" name="name" id="name" class="ui-state-default required text" /></td>
		<td>This is the name of the attribute, e.g. Colour</td>
	</tr>
	<tr>
		<td><label for="datatype">Data Type:</label></td>
		<td><select name="datatype" id="datatype" class="ui-state-default required">
				<option selected="selected" disabled="disabled"></option>
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
</table>
<input type="submit" value="Create" />
</form>
<script>$('#form').validate();</script></body></html>