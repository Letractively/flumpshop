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
 *  @Name models/setup_stage_paths.inc
 *  @Version 1.00
 *  @author Lloyd Wallis <flump5281@gmail.com>
 *  @copyright Copyright (c) 2009-2012, Lloyd Wallis
 *  @package Flumpshop
 */
$tree = 'messages';
$additional_fields = array('config_human_name', 'config_human_description');
$display_entities = array('name', 'tagline', 'email', 'address');

$data = array(
    'heading' => 'About You',
    'summary' => 'On this page, I ask you a little bit about your business, and then for the information I need to set you up so you can login to the Flumpshop Administrator Control Panel (ACP).',
    'form_action' => '../process/doAbout.php',
    'fields' => $_SESSION['config']->getNodes($tree, $additional_fields)
);

foreach (array_keys($data['fields']) as $i => $j) {
  //Don't show the fields that aren't set to display
  if (!in_array($data['fields'][$i]['config_key'], $display_entities)) {
    unset($data['fields'][$i]);
    continue;
  }
  //Change address to a textarea field
  if ($data['fields'][$i]['config_key'] == 'address') {
    $data['fields'][$i]['input_type'] = 'textarea';
  } else {
    $data['fields'][$i]['input_type'] = 'text';
  }
  //Get the default value and put it in the form
  $value = $_SESSION['config']->getNode($tree, $data['fields'][$i]['config_key']);
  $data['fields'][$i]['value'] = $value;
}

//Add the custom fields to this stage
//ACP
$data['fields'][] = array(
    'input_type' => 'text',
    'config_key' => 'acp_admin_user',
    'value' => 'admin',
    'config_human_name' => 'ACP Username',
    'config_human_description' => 'The is the username of the default account that I will create for you in the Flumpshop ACP.',
);
$data['fields'][] = array(
    'input_type' => 'password',
    'config_key' => 'acp_admin_password',
    'value' => '',
    'config_human_name' => 'ACP Password',
    'config_human_description' => 'This is the password of the account I will create for you to access the Flumpshop ACP.'
);
$data['fields'][] = array(
    'input_type' => 'password',
    'config_key' => 'acp_admin_confirmpass',
    'value' => '',
    'config_human_name' => 'Confirm ACP Password',
    'config_human_description' => 'Please re-enter the password you entered in the field above to make sure that you have typed it correctly.'
);
//ACP Tier2
$data['fields'][] = array(
    'input_type' => 'password',
    'config_key' => 'acp_tier2_password',
    'value' => '',
    'config_human_name' => 'Tier 2 Password',
    'config_human_description' => 'This password, also called the Second Tier password, is requested when administrative users try to use the more advanced features of the ACP.'
);
$data['fields'][] = array(
    'input_type' => 'password',
    'config_key' => 'acp_tier2_confirmpass',
    'value' => '',
    'config_human_name' => 'Confirm Tier 2 Password',
    'config_human_description' => 'Please re-enter the password you entered in the field above to make sure that you have typed it correctly.'
);