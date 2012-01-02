<?php
/**
 *  This file is the Controller that process the input from the about stage
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
 *  @Name public/admin/setup/process/doAbout.php
 *  @Version 2.00
 *  @author Lloyd Wallis <flump5281@gmail.com>
 *  @copyright Copyright (c) 2009-2012, Lloyd Wallis
 *  @package Flumpshop
 */
require_once '../../../../includes/setup/lib.inc';

$tree = 'messages';
$navigation_stage = '3.1';
$next_stage = getNextStage(3);

//Remove non-messages tree data from the $_POST variable
$_USER = array(
    'auser' => $_POST['acp_admin_user'],
    'apass' => $_POST['acp_admin_password'],
    'apass2' => $_POST['acp_admin_confirmpass'],
    't2pass' => $_POST['acp_tier2_password'],
    't2pass2' => $_POST['acp_tier2_confirmpass'],
    );
unset($_POST['acp_admin_user'],
        $_POST['acp_admin_password'],
        $_POST['acp_admin_confirmpass'],
        $_POST['acp_tier2_password'],
        $_POST['acp_tier2_confirmpass']);

//Process the data left in $_POST and store it in the message Config tree
require '../../../../models/setup_stage_process_generic.inc';

//Perform sanity checks on the passwords
if ($_USER['apass'] != $_USER['apass2']
        or $_USER['t2pass'] != $_USER['t2pass2']) {
  echo '<h1>Oops!</h1><p>It looks like your passwords didn\'t quite add up. Please re-enter the login details and try again.</p>';
  require '../stages/about.php';
  exit;
}

//Commit user information
require '../../../../models/setup_stage_process_about.inc';

require '../../../../views/setup_stage_process_referrer.inc';