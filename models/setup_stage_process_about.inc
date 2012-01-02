<?php
/**
 *  This custom model for the about stage sets the user account information
 * needed for Flumpshop to get started. It does NOT deal with $_POST information
 * so controllers need to call the setup_stage_process_generic model as well.
 * 
 * It expects necessary information to be sent to it in a $_USER array, with
 * at least the following keys:
 * - t2pass - The password for Tier 2 authentication (Stored in config)
 * - auser - The username for the Admin account to be created
 * - apass - The password for the Admin account to be created
 *
 *  This file is part of Flumpshop.
 *
 *  Flumpshop is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  Flumpshop is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with Flumpshop.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
 *  @Name models/setup_stage_process_about.inc
 *  @Version 1.00
 *  @author Lloyd Wallis <flump5281@gmail.com>
 *  @copyright Copyright (c) 2009-2012, Lloyd Wallis
 *  @package Flumpshop
 */

//Set the configuration password
$_SESSION['config']->setNode('site','password',sha1($_USER['t2pass']));

//Set up the new user
$config =& $_SESSION['config'];
require_once dirname(__FILE__) . '/../includes/Database.class.php';

$dbConn = db_factory();

$dbConn->query('INSERT INTO acp_login (uname, pass,
  can_add_products, can_edit_products, can_delete_products,
  can_add_categories, can_edit_categories, can_delete_categories,
  can_edit_pages, can_edit_delivery_rates, can_post_news,
  can_add_customers, can_contact_customers, can_view_customers,
  can_view_orders, can_create_orders, can_edit_orders, can_assign_orders,
  can_view_reports, can_add_features, can_edit_features, can_delete_features)
  VALUES ("'.$_USER['auser'].'", "'.md5(sha1($_USER['apass'])).'",
        1,1,1,
        1,1,1,
        1,1,1,
        1,1,1,
        1,1,1,1,
        1,1,1,1)');