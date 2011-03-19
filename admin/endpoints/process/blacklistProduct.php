<?php
/**
 *  Blacklists a product
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
 *  @Name admin/endpoints/process/blacklistProduct.php
 *  @Version 1.00
 *  @author Lloyd Wallis <flump5281@gmail.com>
 *  @copyright Copyright (c) 2009-2011, Lloyd Wallis
 *  @package Flumpshop
 */
$requires_tier2 = true;
require_once dirname(__FILE__)."/../header.php";

$id = intval($_POST['item_id']);

$dbConn->query('INSERT INTO product_popular_blacklist VALUES ("'.$id.'")');

header('Location: ../advanced/popularBlacklist.php');
?>