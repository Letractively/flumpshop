<?php
$USR_REQUIREMENT = "can_edit_categories";
require_once "../header.php";

$feature = intval($_GET['feature']);
$category = intval($_GET['category']);

$dbConn->query("DELETE FROM `category_feature` WHERE category_id=$category AND feature_id=$feature LIMIT 1");
?><script type='text/javascript'>window.location='../edit/manageFeatures.php';</script>
<div class='ui-state-highlight'>Operation completed. Redirecting...</div>
</body></html>