<?php
require_once "header.inc.php";
?><h2>Upgrade</h2>
<p>The latest upgrade file is now being downloaded. If you are having issues with this feature, please download the file http://flumpshop.googlecode.com/files/upgrade_v<?php echo $_SESSION['latestVersion'];?>.fml file and place it in the offline directory.</p>

<p>Remember that PHP must have WRITE access to the Flumpshop root directory and all subdirectories for this process.</p>

<p><center id="downloader"><img src="../../../images/loading.gif" /><br />
Downloading upgrade_v<?php echo $_SESSION['latestVersion'];?>.fml</center></p>
<script type="text/javascript">$('#downloader').load('downloader.php?file=upgrade_v<?php echo $_SESSION['latestVersion'];?>.fml');</script>