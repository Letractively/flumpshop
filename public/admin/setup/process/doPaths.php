<?php

/**
 *  This file is the Controller that process the input from the paths stage
 * of the setup wizard. It may re-call the paths Controller if its sanity checks
 * deem it necessary.
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
require '../../../../includes/setup/lib.inc';

$tree = 'paths';
$navigation_stage = '2.2';
$next_stage = 'database.php';
/*
 * Call the model to store the data - even if it's incorrect we'll want the form
 * controller to reload it
 */
require '../../../../models/setup_stage_process_generic.inc';

//Run sanity checks to ensure the given paths are writable
if (!is_writable($_POST['offlineDir'])) {
  echo '<h1>Oops!</h1><p>As part of making this installation "John-proof", I just ran an extra test. Unfortunately, it appears that the Offline Directory either doesn\'t exist, or I don\'t have the necessary permissions to write to it.';
  require '../stages/paths.php';
  exit;
}

if (!is_writable($_POST['logDir'])) {
  echo '<h1>Oops!</h1><p>As part of making this installation "Lloyd-proof", I just ran an extra test. Unfortunately, it appears that the Log Directory either doesn\'t exist, or I don\'t have the necessary permissions to write to it.';
  require '../stages/paths.php';
  exit;
}

//Sanity checks passed. Forward to next controller.
require '../../../../views/setup_stage_process_referrer.inc';