<?php
$requires_tier2 = true;
require_once "../header.php";

//Create the new user account
if (!$dbConn->query("INSERT INTO `acp_login` (uname,
										 pass,
										 pass_expires,
										 can_add_products,
										 can_edit_products,
										 can_delete_products,
										 can_add_categories,
										 can_edit_categories,
										 can_delete_categories,
										 can_edit_pages,
										 can_edit_delivery_rates,
										 can_post_news,
										 can_add_customers,
										 can_contact_customers,
										 can_view_customers,
										 can_view_orders,
										 can_edit_orders,
										 can_view_reports)
			   VALUES
			   							('".htmlentities($_POST['uname'],ENT_QUOTES)."',
										 '".md5(sha1($_POST['pass']))."',
										 '".$dbConn->time(time()+3600*24*30/*30 Days*/)."',
										 ".intval(isset($_POST['can_add_products'])).",
										 ".intval(isset($_POST['can_edit_products'])).",
										 ".intval(isset($_POST['can_delete_products'])).",
										 ".intval(isset($_POST['can_add_categories'])).",
										 ".intval(isset($_POST['can_edit_categories'])).",
										 ".intval(isset($_POST['can_delete_categories'])).",
										 ".intval(isset($_POST['can_edit_pages'])).",
										 ".intval(isset($_POST['can_edit_delivery_rates'])).",
										 ".intval(isset($_POST['can_post_news'])).",
										 ".intval(isset($_POST['can_add_customers'])).",
										 ".intval(isset($_POST['can_contact_customers'])).",
										 ".intval(isset($_POST['can_view_customers'])).",
										 ".intval(isset($_POST['can_view_orders'])).",
										 ".intval(isset($_POST['can_edit_orders'])).",
										 ".intval(isset($_POST['can_view_reports'])).")
										")) {
	$errorString = "?error=An error occured executing a query, and the user creation failed. Please ensure the database structure is up to date, particularly that DBUpgrade_v5.sql has been executed."; } else {
	$errorString = "?error=User created successfully.";
}

header("Location: ../advanced/addUser.php".$errorString);									 
?>