<?php
$logger = true;
require_once dirname(__FILE__)."/../endpoints/header.php";
if (!isset($_GET['file'])) {
	?>
    <div class="ui-widget-header">Miscellaneous Logs</div><div class="ui-widget-content">
    <a href="?file=errors.log&type=text" onclick="$(body).html(loadMsg('Opening Log...'));$('#empty').html(null)">Error Log</a><br />
    <a href="?file=debug.log&type=text" onclick="$(body).html(loadMsg('Opening Log...'));$('#empty').html(null);">Debug Log</a><br />
    <div class="ui-widget-header">Database Logs</div><div class="ui-widget-content">
    <?php
	$dir = opendir($config->getNode('paths','logDir'));
	if ($config->getNode('temp','simplexml')) {
		while ($file = readdir($dir)) {
			if (is_dir($config->getNode('paths','logDir')."/$file") && $file != "." && $file != "..") {
				?>
				<a href="javascript:void(0);" onclick="$('#adminContent').html(loadingString);$('#empty').html(null);$('#adminContent').load('./logs/index.php?file=dbviewer&date=<?php echo $file; ?>');"><?php echo $file; ?></a><br />
				<?php
			}
		}
	} else {
		echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-gear'></span>Sorry, I can't log database activity without the SimpleXML Extension.</div>";
	}
	echo "</div>";
} else {
	$file = $_GET['file'];
	if (strtolower($file) == "dbviewer") {
		$date = $_GET['date'];
		if (!file_exists($config->getNode('paths','logDir')."/$date")) {
			die("There are no logs for the specified date.");
		}
		echo "<div class='ui-widget-header'>Database Logs for $date</div>";
		$dir = opendir($config->getNode('paths','logDir')."/$date");
		readdir($dir); readdir($dir);
		echo "<div class='ui-widget-content'>";
		while ($file = readdir($dir)) {
			?>
            <a href="javascript:void(0);" onclick="$('#adminContent').html(loadingString);$('#empty').html(null);$('#adminContent').load('./logs/index.php?file=<?php echo "$date/$file"; ?>&type=xml');"><?php echo $file; ?></a><br />
            <?php
		}
		echo "</div>";
	} else {
		if (!file_exists($config->getNode('paths','logDir')."/$file")) {
			die("<div class='ui-state-error'><span class='ui-icon ui-icon-help'></span>I can't find that logfile. Looks like nothing has ever happened that needed to be reported.</div>");
		}
		if (isset($_GET['type'])) $type = $_GET['type']; else $type = "text";
		if (strtolower($type) == "text") {
			?>
            <div class="ui-widget-header">Viewing <?php echo $file;?></div><div class="ui-widget-content">
            <?php echo nl2br(file_get_contents($config->getNode('paths','logDir')."/$file"));?>
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
            $log = new SimpleXMLElement($config->getNode('paths','logDir')."/$file",NULL,true);
			$result = false;
			foreach ($log->children() as $event) {
				$attr = $event->attributes();
				if ($filter == false or $attr->type == $mode) {
					$result = true;
					if ($attr->type == "query") {
						echo "<tr class='ui-state-default'><td>".$event->timestamp."</td><td>".urldecode($event->message)."</td></tr>";
					} elseif ($attr->type == "error") {
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