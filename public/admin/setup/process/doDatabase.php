<?php
/**
 *  This file is the Controller that process the input from the database stage
 * of the setup wizard. It may re-call the previous Controller if its sanity
 * checks deem it necessary.
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
 *  @Name public/admin/setup/process/doPaths.php
 *  @Version 2.00
 *  @author Lloyd Wallis <flump5281@gmail.com>
 *  @copyright Copyright (c) 2009-2012, Lloyd Wallis
 *  @package Flumpshop
 */
require_once '../../../../includes/setup/lib.inc';

$tree = 'database';
$navigation_stage = '2.3';
$next_stage = 'about.php';
/*
 * Call the model to store the data - even if it's incorrect we'll want the form
 * controller to reload it
 */
require '../../../../models/setup_stage_process_generic.inc';

//Test the Database connection
require_once '../../../../includes/Database.class.php';
$dbConn = db_factory($_SESSION['config']);

if ($dbConn->error() != '') {
  //Database connection failed
  echo '<h1>Oops!</h1><p>I couldn\'t connect to the database. Please check your details and try again.</p>';
  echo '<p>', $dbConn->error(), '</p>';
  require '../stages/database.php';
  exit;
}

require '../../../../models/setup_configure_db.inc';
require '../../../../views/setup_stage_process_db.inc';
