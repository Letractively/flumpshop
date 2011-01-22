<?php
$requires_tier2 = true;
require_once "../header.php";
$dbConn->query("DELETE FROM cache");
$config->removeTree('cache');
?><h1>Clear Cache</h1>
<p>The Flumpshop cache has been cleared.</p>
</body></html>