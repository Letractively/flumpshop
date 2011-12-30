<h1>Compatibility Checker</h1>
<p>Below, I've listed anything about your current server environment that I think should be bought to your attention. If you see a big red stripy box of doom, then it means that something is really wrong, and that you have to fix it before you can carry on. After that, click 'Customise' to do just that to your setup experience.</p><?php

if (!sizeof($fail) == 0) {
	echo "<div class='ui-widget ui-state-error'><div class='ui-widget-header ui-state-error'>Flumpshop needs the following resolved before it can continue</div><div class='ui-widget-content ui-state-error'>";
	foreach ($fail as $failure) {
			echo $failure."<br />";
	}
	echo "</div></div><br />";
}
if (!sizeof($warn) == 0) {
	echo "<div class='ui-widget ui-state-highlight'><div class='ui-widget-header'>Configuring these will enable advanced features</div><div class='ui-widget-content'>";
	foreach ($warn as $win) {
			echo $win."<br />";
	}
	echo "</div></div><br />";
}
if (!sizeof($success) == 0) {
	echo "<div class='ui-widget ui-state-default'><div class='ui-widget-header'>The following allow you to use additional features</div><div class='ui-widget-content'>";
	foreach ($success as $win) {
			echo $win."<br />";
	}
	echo "</div></div><br />";
}
if (!sizeof($fail) == 0) {
	echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-alert'></span>I need the above issue(s) fixed before I can continue! <a href='javascript:history.go(0);'>Try Again</a></div>";
} else {
	echo "<a onclick=\"parent.leftFrame.window.location='../?frame=leftFrame&p=1.3';\" href='./customise.php'><div class='ui-state-highlight'><span class='ui-icon ui-icon-circle-check'></span>Customise your setup experience</div></a>";
}