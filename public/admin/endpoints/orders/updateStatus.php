<?php
require_once "../../../preload.php";
if (!acpusr_validate('can_edit_orders')) die();

echo json_encode($dbConn->query("UPDATE orders SET status='".intval($_GET['status'])."' WHERE id='".intval($_GET['order'])."' LIMIT 1"));
?>