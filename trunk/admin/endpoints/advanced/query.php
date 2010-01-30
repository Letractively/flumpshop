<?php require_once dirname(__FILE__)."/../header.php";?><div class="ui-widget-header">Execute SQL</div>
<div class="ui-widget-content"><p>This feature allows you to dig deep into the base system. It is a REALLY bad idea to do this, unless you are really sure that what you want to do can't be accomplished using one of the buttons, all safely validated by the Flumpnet Robot. If you wish to directly query the database, enter your query below.</p>
<div class="ui-state-highlight"><span class="ui-icon ui-icon-lightbulb"></span>Not sure what you're doing but going to do it anyways? Make sure you export that data first in case you break it.</div>
<form action="../process/execute.php" method="post" onsubmit="$(body).html('Executing Query...<br />Depending on several factors, this may take several minutes.');">
<textarea name="query" id="query" class="ui-widget-content" style="width: 100%; height: 300px;"></textarea><br />
<input type="submit" value="Execute" style="font-size: 12px; padding: .2em .4em;" />
</form></div></body></html>