<?php
/**
 *  A very simple controller - no model is needed as only one piece of data
 * is needed, and that's in the session variable
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
 *  @Name public/admin/setup/welcome/customise.php
 *  @Version 2.00
 *  @author Lloyd Wallis <flump5281@gmail.com>
 *  @copyright Copyright (c) 2009-2012, Lloyd Wallis
 *  @package Flumpshop
 */

require '../../../../includes/setup/header.inc.php';
if (isset($_GET['mode'])) {
  //This form has been submitted. Process it and forward to the next page
  if (!isset($_SESSION)) session_start();
  require '../../../../models/setup_configure_wizard.inc';
  require '../../../../views/setup_configure_wizard.inc';
} else {
  require '../../../../views/setup_customise.inc';
}