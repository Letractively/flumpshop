<?php
require_once dirname(__FILE__)."/../header.php";

$result = $dbConn->query("SELECT * FROM `country` ORDER BY name ASC");
?><div class="ui-widget-header">Supported Countries</div>
<form action="../process/saveCountries.php" method="POST" onsubmit="$(body).html(loadMsg('Saving Content...'));" class="ui-widget-content">
        <table>
        	<tr>
            	<th>Country</th>
                <th>Delivery</th>
            </tr>
            <?php
			while ($row = $dbConn->fetch($result)) {
				if ($row['supported'] == 1) {
					$checked = " checked='checked'";
					$checked2 = "";
				} else {
					$checked = "";
					$checked2 = " checked='checked'";
				}
				echo "<tr><td><label for='".$row['iso']."'>".$row['name']."</label></td><td id='".$row['iso']."' class='buttonSet'>";
				echo "<input type='radio' name='".$row['iso']."' id='".$row['iso']."on' class='on' value='On'$checked />";
				echo "<label for='".$row['iso']."on'>On</label>";
				echo "<input type='radio' name='".$row['iso']."' id='".$row['iso']."off' class='off' value='Off'$checked2 />";
				echo "<label for='".$row['iso']."off'>Off</label></td>";
			}
			?>
        </table>
        <script type="text/javascript">
		$('.buttonSet').each(function() {$(this).buttonset();});
		</script>
        <input type="submit" value="Save" class="ui-widget-content" />
</form></body></html>