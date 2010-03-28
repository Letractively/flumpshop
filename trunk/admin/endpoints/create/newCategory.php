<?php
$USR_REQUIREMENT = "can_add_categories";
require_once dirname(__FILE__)."/../header.php";
?><div class="ui-widget-header">Add Category</div>
<form action="../process/insertCategory.php" method="post" class="ui-widget-content" onsubmit="if ($(this).valid()) {loader(loadMsg('Saving Content...')); return true;} else return false;">
<table>
	<tr>
		<td><label for="name">Name:</label></td>
		<td><input type="text" maxlength="255" name="name" id="name" class="ui-widget-content ui-state-default required" /></td>
		<td>The name of the new category. Maximum length: 255 characters.</td>
	</tr>
	<tr>
		<td><label for="description">Description:</label></td>
		<td><textarea rows="4" cols="45" name="description" id="description" class="ui-widget-content ui-state-default"></textarea></td>
		<td>Information about the category, as shown on the category page. This can be as long as you want, and fully supports HTML.</td>
	</tr>
	<tr>
		<td><label for="parent">Parent:</label></td>
		<td><select name="parent" id="parent" class="ui-widget-content">
			<option value="0" selected="selected">No Parent</option>
			<?php
			$result = $dbConn->query("SELECT id FROM `category` ORDER BY `parent` ASC");
			while ($row = $dbConn->fetch($result)) {
				$category = new Category($row['id']);
				echo "<option value='".$category->getID()."'>".$category->getFullName()."</option>";
			}
			?>
			</select>
		</td>
		<td>The new category will become a subcategory of the category chosen here. If No Parent is chosen, it will be a top-level category, and appear in the main navigation bar.</td>
	</tr>
</table>
<input type="submit" value="Save" name="submit" id="submit" style="font-size: 13px; padding: .2em .4em;" />
</form></body></html>