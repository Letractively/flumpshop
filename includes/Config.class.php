<?php

/**
 *  Provides global logic and storage for sitewide data
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
 *  @Name Config.class.php
 *  @Version 2.0
 *  @author Lloyd Wallis <flump@flump.me>
 *  @copyright Copyright (c) 2009-2012, Lloyd Wallis
 *  @package	Flumpshop
 */
class Config {

  /**
   * The $name variable is the name of the Configuration set - the database
   * can store multiple unique configurations, and this tells the object which
   * set should be used.
   * @var string $name Configuration set name
   * @since 1.0
   */
  private $name = 'Default';

  /**
   * The $set_id variable contains the internal ID of the set name $name
   * @var int $set_id Configuration set ID
   * @since 2.0 
   */
  private $set_identifier = 0;

  /**
   * The $data variable caches elements of the configuration store that have
   * been retrieved in the object's lifetime so that it doesn't have to query
   * again if the element is used multiple times
   * @var array $data Cached configuration store data
   * @since 1.0
   */
  private $data = array();

  /**
   * The $human_readable array is initialised and used if $commit is false. It
   * provides temporary storage of human-readable names and descriptions of
   * configuration values that will be written if $this->commitAll() is called
   * @var array $human_readable Human readable message for the key-value maps
   * @since 2.0
   */
  private $human_readable;

  /**
   * The $commit variable defines whether or not the object auto-commits
   * changes to the configuration into the configuration store. This is useful
   * for the setup wizard when these requests will just fail, and the object
   * is stored elsewhere anyway
   * @var boolean $commit Whether data can be commited to the DB
   * @since 2.0
   */
  private $commit = true;

  /**
   * Config constructor.
   * @since 1.0
   * @param string $name Optional. A name to assign to the configuration object. Currently not used. Default "Default".
   * @param bool $debug Optional. Whether to output debugging data when executiong functions. Default false.
   * @return void No return value.
   */
  public function Config($name = null, $commit = null) {
    if (is_null($name))
      $name = 'Default Configuration';
    if (is_null($commit))
      $commit = true;

    $this->name = $name;
    $this->commit = $commit;
    if (!$commit)
      $this->human_readable = array();
    else {
      //Get or set the configuration set ID
      global $dbConn;
      $dbConn->query('REPLACE INTO config_sets  (set_name)
        VALUES ("' . $name . '")');
      $this->set_identifier = (int) $dbConn->insert_id();
    }
  }

  /**
   * Adds a new configuration tree to the configuration store. $description
   * @param string $treeName The internal name of the new tree
   * @param string $friendName A human-readable name of the tree
   * @param boolean $purge If true, any existing values in this tree are deleted
   * @param string $description A human-readable explanation of the tree
   * @return type 
   */
  public function addTree($treeName, $friendName, $description, $purge = null) {
    if (is_null($purge))
      $purge = false;

    //Purge the configuration tree's contents if requested
    if ($purge)
      $this->purge_tree($treeName);

    //Replace the Tree configuration data in the database
    return $this->configure_tree($treeName, $friendName, $description);
  }

  /**
   * Purges the given configuration tree, so it has no values within it
   * @param string $treeName The tree to purge
   * @return boolean Whether the operation was successful 
   */
  private function purge_tree($treeName) {
    //Report Progress
    debug_message('Purging Tree ' . $treeName);

    $this->data[$treeName] = array();
    if ($this->commit) {
      return (boolean) $db->query('DELETE FROM config_values
        WHERE config_set="' . $this->getSetID() . '"
        AND config_tree="' . $treeName . '"');
    }

    //Report Progress
    debug_message('Tree will not be purged in Configuation store');
    return true; //Return true if not committing
  }

  /**
   * Inserts or Updates the configuration store tree with the specified values
   * This is not Configuration Set specific.
   * @global Database $dbConn Database handle
   * @param string $treeName The internal name of the tree
   * @param string $human_name The human-readable name of the tree
   * @param string $human_description The human-readable description of the tree
   * @return boolean Whether the operation was successful 
   * @since 2.0
   */
  private function configure_tree($treeName, $human_name, $human_description) {
    //Report Progress
    debug_message('Configuring Tree ' . $treeName);

    //If commit is enabled, update the configuration store. Otherwise update the
    //local config $human_readable
    if ($this->commit) {
      global $dbConn;
      //escape the human-readable strings
      $human_name = $dbConn->real_escape_string($human_name);
      $human_description = $dbConn->real_escape_string($human_description);
      //Run the query, and return if it was successful
      return (boolean) $dbConn->query('REPLACE INTO config_values_human
        (config_tree, config_human_name, config_human_description) VALUES
        ("' . $treeName . '", "' . $human_name . '", "' . $human_description, '"');
    } else {
      debug_message('Commit mode disabled. Tree not commited to store.');
      $this->human_readable[$treeName]['__meta'] = array(
          'config_human_name' => $human_name,
          'config_human_description' => $human_description);
      return true;
    }
  }

  /**
   * Used when submitting the Configuration Manager. Because checkboxes in fields
   * just don't appear in POST when not checked, the system first sets all
   * booleans to false, then sets appropriate ones to true.
   * It is best practice to either start a transaction or disable commits for
   * this process.
   * @param string $tree If defined, only this tree will be reset
   * @return boolean Whether the operation was a success 
   */
  public function falseify($tree = NULL) {
    debug_message('Flumpshop is nullifying the configuration store...');
    //Null wipes the entire object (all trees)
    if ($tree == NULL) {
      foreach ($this->getTrees() as $tree) {
        $this->falseify($tree);
      }
    } else {
      foreach ($this->getNodes($tree) as $node) {
        if ($this->getNode($tree, $node) === true) {
          $this->setNode($tree, $node, false);
        }
      }
    }
    return true;
  }

  /**
   * Returns an alphabetised list of Configuration Trees
   * @global Database $dbConn The database handle
   * @return array An array of Configuration trees
   * @since 1.0
   */
  public function getTrees() {
    //Can't get a complete list as database-free mode is on
    if (!$this->commit) {
      return array();
    }

    global $dbConn;
    $result = $dbConn->query('SELECT config_tree, config_human_name,
      config_human_description FROM config_values_human config_key IS NULL
      ORDER BY config_human_name ASC');
    $data = array();
    while ($row = $dbConn->fetch($result)) {
      $data[] = $row;
    }

    return $data;
  }

  /**
   * Updates the Configuration store element with the given configuration data
   * @param string $treeName The name of the tree the updated elemend is in
   * @param string $nodeName The internal name of the element to update
   * @param mixed $nodeVal The new value of the element
   * @param string $friendName Optional human-readable name of the element
   * @param string $description Optional human-readable description of the element
   * @return boolean Whether the operation was successful 
   */
  public function setNode($treeName, $nodeName, $nodeVal, $friendName = null, $description = null) {
    //Define the node human-readable descriptions, if configured
    if ($friendName !== null or $description !== null) {
      $this->defineNode($treeName, $nodeName, $friendName, $description);
    }

    //Set Value
    $this->data[$treeName][$nodeName] = $nodeVal;
    if ($this->commit) {
      $nodeVal = $dbConn->real_escape_string($nodeVal);
      return $dbConn->query('REPLACE INTO config_values
        (config_set, config_tree, config_key, config_value) VALUES
        (' . $this->getSetID() . ', "' . $treeName . '", "' . $nodeName . '",
          "' . $nodeVal . '")');
    }
    return true;
  }

  /**
   * Defines a configuration element in the Configuration store, providing a
   * human readable name and description.
   * @global Database $dbConn The database handle
   * @param string $treeName The tree the element exists in
   * @param string $nodeName The internal name of the element
   * @param string $human_name The human-readable name of the element
   * @param string $description The human-readable description of the element
   * @return boolean Whether the operation was a success 
   */
  private function defineNode($treeName, $nodeName, $human_name, $description) {
    if (!$this->commit) {
      //Database mode is disabled. Update local repo only
      $this->human_readable[$treeName][$nodeName] = array(
          'config_human_name' => $human_name,
          'config_human_description' => $description
      );
      return true;
    }
    //Prepare the human-readable values to be SQL safe
    global $dbConn;
    $human_name = $dbConn->real_escape_string($human_name);
    $description = $dbConn->real_escape_string($description);

    //Commit the values and return whether it was successful
    return (boolean) $dbConn->query('REPLACE INTO config_values_human
      (config_tree, config_key, config_human_name, config_human_description)
      VALUES ("' . $treeName, '", "' . $nodeName . '", "' . $human_name .
                    '", "' . $description . '")');
  }

  /**
   * Fetches a property from the Configuration store
   * @global Database $dbConn The database handle
   * @param string $treeName The tree the element exists in
   * @param string $nodeName The name of the element to fetch
   * @return mixed The configuration value, or null if it doesn't exist 
   */
  public function getNode($treeName, $nodeName) {
    if (isset($this->data[$treeName][$nodeName])) {
      return $this->data[$treeName][$nodeName];
    }

    if (!$this->commit)
      return null;

    global $dbConn;
    $result = $dbConn->query('SELECT config_value FROM config_values
      WHERE config_set=' . $this->getSetID() . '
        AND config_tree="' . $treeName . '"
          AND config_key="' . $nodeName . '" LIMIT 1');

    if ($dbConn->rows($result) === 0)
      return null;

    $row = $dbConn->fetch($result);
    $this->data[$treeName][$nodeName] = $row['config_value'];

    return $row['config_value'];
  }

  /**
   * Returns whether the given node is defined in the configuration store
   * @param string $treeName The tree the node should reside in
   * @param string $nodeName The internal name of the node to check
   * @return boolean Whether the configuration value exists 
   */
  public function isNode($treeName, $nodeName) {
    if (isset($this->data[$treeName][$nodeName]))
      return true;
    if (!$this->commit)
      return false;

    return (boolean) $dbConn->rows(
                    $dbConn->query('SELECT config_name FROM config_values
              WHERE config_set=' . $this->getSetID() . '
                AND config_tree="' . $treeName . '"
                  AND config_key="' . $nodeName . '" LIMIT 1'));
  }

  /**
   * Returns if the given string is a valid Configuration store tree
   * @param string $treeName The internal name of the tree to check
   * @return boolean Whether the tree exists in the configuration store 
   */
  public function isTree($treeName) {
    if (isset($this->data[$treeName]))
      return true;
    if (!$this->commit)
      return false;

    return (boolean) $dbConn->rows(
                    $dbConn->query('SELECT config_name FROM config_values
              WHERE config_set=' . $this->getSetID() . '
                AND config_tree="' . $treeName . '" LIMIT 1'));
  }

  /**
   * Returns the human-readable name of a tree of value
   * @param string $treeName The tree to fetch, or the tree the node is in
   * @param string $nodeName Optional. The node to fetch
   * @return string The human-readable name of the element
   */
  public function getFriendName($treeName, $nodeName = null) {
    //Returns the friendly (human-readable) name of the node or tree
    debug_message('Getting Friendly Name for ' . $treeName . '|' . $nodeName);

    if (!$this->commit) {
      //Commit mode is disabled. Check the local cache instead
      if ($nodeName === null) {
        return $this->human_readable[$treeName]['__meta']['config_human_name'];
      } else {
        return $this->human_readable[$treeName][$nodeName]['config_human_name'];
      }
    }

    //Prepare the where clause for the query
    if ($nodeName === null) {
      $where = ' IS NULL';
    } else {
      $where = '="' . $nodeName . '"';
    }

    $result = $dbConn->query('SELECT config_human_name FROM config_values_human
      WHERE config_tree="' . $treeName . '" AND config_value' . $nodeName . ' LIMIT 1');

    if ($dbConn->rows($result) === 0)
      return 'Unknown Element';

    $row = $dbConn->fetch($result);
    return $row['config_human_name'];
  }

  /**
   * Returns all possible Configuration nodes for the given tree
   * @global Database $dbConn The database handle
   * @param string $treeName The internal name of the tree
   * @param array $additional_fields Additional fields to return from the store
   * @return array An array of keys sorted alphabetically by human name 
   * If $additional_fields is defined, returns an array of arrays
   */
  public function getNodes($treeName, $additional_fields = array()) {
    if (!$this->commit) {
      //Commit mode disables database usage. Return cached keys only
      $data = array_keys($this->data[$treeName]);
      
      if (empty($additional_fields)) return $data;
      
      $nodes = array();
      foreach ($data as $key) {
        $node = $this->human_readable[$treeName][$key];
        $node['config_key'] = $key;
        $nodes[] = $node;
      }
      return $nodes;
    }

    $fields = 'config_key';
    foreach ($additional_fields as $additional_field) {
      $fields .= ',' . $additional_field;
    }

    global $dbConn;
    $result = $dbConn->query('SELECT ' . $fields . ' FROM config_values_human
      WHERE config_tree="' . $treeName . '" ORDER BY config_human_name ASC');

    $keys = array();

    while ($row = $dbConn->fetch($result)) {
      //Don't return an array of arrays if there's only 1 field to return
      if (empty($additional_fields)) {
        $row = $row['config_key'];
      }
      //Append the row
      $keys[] = $row;
    }

    return $keys;
  }

  /**
   * The Internal ID of the configuration set
   * @return int The ID of the Configuration set 
   */
  public function getSetID() {
    return $this->set_identifier;
  }

}