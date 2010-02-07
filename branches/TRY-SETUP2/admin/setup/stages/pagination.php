<?php
require_once dirname(__FILE__)."/../header.inc.php";

//Process next stage
echo "<script>window.location = '../stages/".getNextStage(9)."'</script>";
require_once dirname(__FILE__)."/../footer.inc.php";
?>