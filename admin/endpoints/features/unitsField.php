<table>
	<tr>
		<td><label for="unitMultiple<?php echo $_GET['id'];?>">Unit Multiple (e.g. 1000): </label></td>
		<td><input type="text" class="ui-widget-content number required" maxlength="11" name="unitMultiple<?php echo $_GET['id'];?>" /></td>
	</tr>
	<tr>
		<td><label for="unitSuffix<?php echo $_GET['id'];?>">Unit Suffix (e.g. Kg): </label></td>
		<td><input type="text" class="ui-widget-content text required" maxlength="15" name="unitSuffix<?php echo $_GET['id'];?>" /></td>
	</tr>
</table><hr />