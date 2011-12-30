<?php

/**
 *  This file is the initial Controller for the Setup wizard. It handles all 
 * setup requests and.. handles... them accordingly
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
 *  @Name public/admin/setup/index.php
 *  @Version 2.00
 *  @author Lloyd Wallis <flump5281@gmail.com>
 *  @copyright Copyright (c) 2009-2012, Lloyd Wallis
 *  @package Flumpshop
 */
if (!isset($_SESSION))
  session_start();
$_SETUP = true;

/*
 * If the configuration file exists, the user must be Tier 2 authenticated
 * to access the setup wizard
 */
if ((!isset($_SESSION['adminAuth'])
        or $_SESSION['adminAuth'] !== true)
        and file_exists(dirname(__FILE__) . "/../../conf.txt")) {
  header('Location: login.php');
} else {
  //Logged In/No login required
  if (isset($_GET['frame'])) {
    if ($_GET['frame'] == 'leftFrame') {
      //Left Frame
      require '../../../views/setup_frame_left.inc';
    } elseif ($_GET['frame'] == 'header') {
      //Header Frame
      require '../../../views/setup_frame_top.inc';
    } elseif ($_GET['frame'] == 'main') {
      //Main Frame
      require '../../../views/setup_frame_main.inc';
    }
  } else {
    //Output the Frameset commands
    require '../../../views/setup_frame_set.inc';
  }
}