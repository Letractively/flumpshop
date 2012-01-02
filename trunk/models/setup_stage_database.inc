<?php
/**
 *  This model prepares the necessary data required to render a setup stage view
 * It is a very similar model to the other stages, although each will have minor
 * variations due to the nature of the Configuration elements they are loading
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
 *  @Name models/setup_stage_database.inc
 *  @Version 1.00
 *  @author Lloyd Wallis <flump5281@gmail.com>
 *  @copyright Copyright (c) 2009-2012, Lloyd Wallis
 *  @package Flumpshop
 */
$tree = 'database';
$additional_fields = array('config_human_name', 'config_human_description');

$data = array(
    'heading' => $_SESSION['config']->getFriendName($tree),
    'summary' => 'For Flumpshop to store information, it needs to be able to access a MySQL Database. Please enter the connection details here, then I will set up the database so that Flumpshop can use it.',
    'form_action' => '../process/doDatabase.php',
    'fields' => $_SESSION['config']->getNodes($tree, $additional_fields)
);

foreach (array_keys($data['fields']) as $i => $j) {
  //The engine is not an editable field atm
  if ($data['fields'][$i]['config_key'] === 'type') {
    unset($data['fields'][$i]);
    continue;
  }
  //Override the password field so it's a password type object
  if ($data['fields'][$i]['config_key'] === 'password') {
    $data['fields'][$i]['input_type'] = 'password';
  } else {
    //Set all other fields to the default text type
    $data['fields'][$i]['input_type'] = 'text';
  }
  $value = $_SESSION['config']->getNode($tree, $data['fields'][$i]['config_key']);
  $data['fields'][$i]['value'] = $value;
}