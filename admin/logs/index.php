<?php
if (!isset($_GET['file'])) {
	?>
    <div class="ui-widget-header">Miscellaneous Logs</div><div class="ui-widget-content">
	<a href="javascript:void(0);" onclick="$('#adminContent').html(loadingString);$('#empty').html(null);$('#adminContent').load('./logs/index.php?file=database.log&type=text');">Global Database Log</a><br />
    <a href="javascript:void(0);" onclick="$('#adminContent').html(loadingString);$('#empty').html(null);$('#adminContent').load('./logs/index.php?file=errors.log&type=text');">Error Log</a><br />
    <a href="javascript:void(0);" onclick="$('#adminContent').html(loadingString);$('#empty').html(null);$('#adminContent').load('./logs/index.php?file=setup.log&type=text');">Setup Log</a>
    </div>
    <div class="ui-widget-header">Database Logs</div><div class="ui-widget-content">
    <?php
	$dir = opendir(dirname(__FILE__));
	while ($file = readdir($dir)) {
		if (is_dir(dirname(__FILE__)."/$file") && $file != "." && $file != "..") {
			?>
			<a href="javascript:void(0);" onclick="$('#adminContent').html(loadingString);$('#empty').html(null);$('#adminContent').load('./logs/index.php?file=dbviewer&date=<?php echo $file; ?>');"><?php echo $file; ?></a><br />
			<?php
		}
	}
	echo "</div>";
} else {
	$file = $_GET['file'];
	if (strtolower($file) == "dbviewer") {
		$date = $_GET['date'];
		if (!file_exists(dirname(__FILE__)."/$date")) {
			die("There are no logs for the specified date.");
		}
		echo "<div class='ui-widget-header'>Database Logs for $date</div>";
		$dir = opendir(dirname(__FILE__)."/$date");
		readdir($dir); readdir($dir);
		echo "<div class='ui-widget-content'>";
		while ($file = readdir($dir)) {
			?>
            <a href="javascript:void(0);" onclick="$('#adminContent').html(loadingString);$('#empty').html(null);$('#adminContent').load('./logs/index.php?file=<?php echo "$date/$file"; ?>&type=xml');"><?php echo $file; ?></a><br />
            <?php
		}
		echo "</div>";
	} else {
		if (!file_exists(dirname(__FILE__)."/$file")) {
			die("The selected logfile could not be found.");
		}
		if (isset($_GET['type'])) $type = $_GET['type']; else $type = "text";
		if (strtolower($type) == "text") {
			?>
            <div class="ui-widget-header">Viewing <?php echo $file;?></div><div class="ui-widget-content">
            <?php echo nl2br(file_get_contents(dirname(__FILE__)."/$file"));?>
            </div>
            <?php
		} elseif (strtolower($type) == "xml") {
			if (isset($_GET['mode'])) {
				$filter = true;
				$mode = $_GET['mode'];
			} else {
				$filter = false;
				$mode = "";
			}
			?>
            <div class="ui-widget-header">Database Log <?php echo $file; ?></div>
            <?php
			 if ($filter) {
				 ?>
                 <div class="ui-state-highlight"><span class="ui-icon ui-icon-info"></span>Only showing errors - <a href="javascript:void(0);" onclick="$('#adminContent').html(loadingString);$('#empty').html(null);$('#adminContent').load('./logs/index.php?file=<?php echo "$file"; ?>&type=xml');">Show All</a>
                 <?php
			 } else {
				 ?>
                 <div class="ui-state-highlight"><span class="ui-icon ui-icon-info"></span>Showing all messages - <a href="javascript:void(0);" onclick="$('#adminContent').html(loadingString);$('#empty').html(null);$('#adminContent').load('./logs/index.php?file=<?php echo "$file"; ?>&type=xml&mode=error');">Show Errors Only</a></div>
                <?php
			 }
			 ?>
            <table class="ui-widget-content">
            <tr><th>Timestamp</th><th>Message</th>
            <?php
            $log = new SimpleXMLElement(dirname(__FILE__)."/$file",NULL,true);
			$result = false;
			foreach ($log->children() as $event) {
				if ($filter == false or $event->attributes()->type == $mode) {
					$result = true;
					if ($event->attributes()->type == "query") {
						echo "<tr class='ui-state-default'><td>".$event->timestamp."</td><td>".urldecode($event->message)."</td></tr>";
					} elseif ($event->attributes()->type == "error") {
						echo "<tr class='ui-state-error'><td>".$event->timestamp."</td><td>".urldecode($event->message)."</td></tr>";
					}
				}
			}
			?>
            </table>
            <?php
			if (!$result) {
				?>
                <div class="ui-state-highlight"><span class="ui-icon ui-icon-info"></span>There are no messages of the specified type.</div>
                <?php
			}
		} else {
			echo "That log type is currently not supported.";
		}
	}
}
?>