<?php
require_once dirname(__FILE__)."/../header.php";

$result = $dbConn->query("SELECT * FROM `country` ORDER BY name ASC");
?><form action="../process/saveCountries.php" method="POST" onsubmit="loader(loadMsg('Saving Content...'));" class="ui-widget-content">
        <fieldset>
        <legend>Supported Countries</legend>
        <span class='ui-state-highlight'>Select: <a href='javascript:' onclick="$('input').attr('checked',true);">All</a> | <a href='javascript:' onclick="$('input').attr('checked',false);">None</a> | <a href='javascript:' onclick="$('input').each(function() {$(this).attr('checked',!$(this).attr('checked'))});">Invert</a></span>
        <table>
                <tr>
                <th>Country</th>
                <th>Delivery</th>
            </tr>
            <?php
                        while ($row = $dbConn->fetch($result)) {
                                if ($row['supported'] == 1) $checked = " checked='checked'"; else $checked="";
                                echo "<tr><td><label for='".$row['iso']."'>".$row['name']."</label></td><td><input type='checkbox' name='".$row['iso']."' id='".$row['iso']."'$checked /></td>";
                        }
                        ?>
        </table>
        <input type="submit" value="Save" class="ui-widget-content" />
    </fieldset>
</form>
</body></html>