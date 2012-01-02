<?php

/*
 * =============================================================
 *  Name        : Database.class.php
 *  Description : Provides a single interface for multiple
  database engines
 *  Version     : 1.1-conf
 *
 *  Copyright (c) 2008-2010 Lloyd Wallis, flump@flump.me
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
 * ====================================================================
 */

/**
 * Creates the necessary database subclass for the Flumpshop Instance
 * @since 1.1
 * @param Uses Flumpshop Superglobals to fetch necessary details.
 * @return An instantiated Database Object
 */
function db_factory($cfg) {
  return new MysqliDatabase($cfg->getNode('database', 'address'),
                  $cfg->getNode('database', 'uname'),
                  $cfg->getNode('database', 'password'),
                  $cfg->getNode('database', 'name'),
                  $cfg->getNode('database', 'port'));
}

/**
 * The parent class for all database connection types.
 *  Initialises logs and shared functions.
 */
interface Database {

  /**
   * Returns the last error reported by a Database subclass.
   * @since 1.0
   * @param No arguments.
   * @return Returns a string description of the last error encountered by a Database subclass
   */
  function error();

  /**
   * Builds an SQL-format timestamp.
   * @since 1.0
   * @param $time. Optional. The time which the timestamp will be created for. Defaults to now.
   * @return An SQL-formatted timestamp.
   * @todo THIS FUNCTION APPEARS TO BE VERY SLOW. AVOID USING WHERE POSSIBLE
   */
  function time($time = 0);

  /**
   * Can be used for statistical purposes.
   * @since 1.0
   * @param No arguments.
   * @return The number of queries executed by the Database subclass
   */
  function getQueryCount();
}

/**
 * MySQL Extension.
 * Extends the Database class for MySQLi database connections.
 */
class MysqliDatabase extends mysqli implements Database {

  private $lastError;
  private $queryCount = 0;
  private $xmlLog;

  /**
   * Constructor.
   * @since 1.0
   * @param $addr the IP Address of FQDN of the MySQL server.
   * @param $uname the username for the MySQL server Login.
   * @param $pass the password for the MySQL server Login.
   * @param $port the port used to connect to the MySQL server. Optional (default 3306).
   * @param $db the name of the database to select. Optional (default none).
   * @return No return value
   */
  function __construct($addr, $uname, $pass, $db = '', $port = 3306) {
    global $_SETUP;
    $this->xmlLog = new DatabaseLogger();
    parent::__construct($addr, $uname, $pass, $db, $port);
    if ($this->connect_error) {
      $this->lastError = $this->connect_error;
      $this->xmlLog->log($this->lastError, 'error');
      trigger_error('MySQL Error: Failed to connect to the MySQL Server: ' . $this->lastError);

      if (!$_SETUP) {
        header('Location: ' . $GLOBALS['config']->getNode('paths', 'root') . '/500.php?config=&error=' . base64_encode($this->lastError));
        exit;
      }
    }
  }

  public function time($time = 0) {
    if ($time === 0)
      return date("Y-m-d H:i:s");
    else {
      if (!is_int($time))
        $time = strtotime($time);
      return date("Y-m-d H:i:s", $time);
    }
  }

  /**
   * Parses and executes an SQL Query.
   * @since 1.0
   * @param $str the SQL Query.
   * @param $debug whether to output detailed information and errors. Optional (default false).
   * @return DatabaseResult object on success, false on failure.
   */
  public function query($str, $debug = false) {
    $this->xmlLog->log($str, 'query');

    $result = parent::query($str);
    if (!$result) {
      $caller = debug_backtrace(false);
      $this->lastError = '<div class="ui-state-error">'.
              $this->error . " (Called by " . $caller[0]['file'] .
              ":" . $caller[0]['line'] . ") when executing $str</div>";
      $this->xmlLog->log($this->lastError, 'error');
      trigger_error('MySQL Error: ' . $this->lastError);
      if ($debug)
        echo nl2br(print_r($caller,true));
    }
    $this->queryCount++;
    return $result;
  }

  public function error() {
    return $this->lastError;
  }

  public function getQueryCount() {
    return $this->queryCount;
  }

  /**
   * Execute a multi-line SQL Query on the server.
   * @since 1.1
   * @param $str the SQL Query.
   * @param $debug whether to output detailed information and erros. Optional (default false).
   * @return false if no connection, true otherwise.
   */
  function multi_query($str, $debug = NULL) {
    $this->xmlLog->log($str, 'query');
    return parent::multi_query($str);
  }

  /**
   * Fetch a row from the specified result set.
   * @since 1.0
   * @param $resource a MySQLi result resource.
   * @return An array containing a row from the result set, or false on failure.
   * @deprecated
   */
  function fetch($resource) {
    echo '<div class="ui-state-error">Call to deprecated function MysqliDatabase::fetch()</div>';
    return $resource->fetch_assoc();
  }

  /**
   * Get the number of rows in a result set.
   * @since 1.0
   * @param $resource a MySQLi result resource.
   * @return An integer specifying the number of rows in the result set (0 on failure)
   * @deprecated
   */
  public function rows($resource) {
    echo '<div class="ui-state-error">Call to deprecated function MysqliDatabase::rows()</div>';
    return $resource->num_rows;
  }

  /**
   * Gets the number of rows affected by the last operation
   * @since 1.2
   * @return int The number of rows affected by the last operation
   * @deprecated
   */
  function affected_rows() {
    echo '<div class="ui-state-error">Call to deprecated function MysqliDatabase::affected_rows()</div>';
    return $this->affected_rows;
  }

  /**
   * The version of the MySQL Server.
   * @since 1.1
   * @param No arguments.
   * @return Description of the MySQL Server environment.
   */
  public function version() {
    return $this->server_info;
  }

}

class DatabaseLogger {

  private $handle;
  private $date;

  function DatabaseLogger() {
    global $config;
    if (!isset($config) or !$config->getNode('logs', 'xmldblog')) {
      $this->handle = false;
      return;
    }
    $date = date("d-m-Y");

    if (!is_dir($config->getNode('paths', 'logDir') . "/" . $date . "/")) {
      mkdir($config->getNode('paths', 'logDir') . "/" . $date);
    }
    $fname = session_id();
    $url = $config->getNode('paths', 'logDir') . "/" . $date . "/" . $fname . ".log.xml";
    if (!file_exists($url)) {
      file_put_contents($url, '<?xml version=\'1.0\'?><log></log>');
    }
    try {
      $this->handle = new SimpleXMLElement($url, NULL, true);
    } catch (Exception $e) {
      trigger_error('Could not load XML File - '.$e->getMessage());
      $this->handle = false;
    }
  }

  function log($msg, $type) {
    if (!$this->handle)
      return;
    $node = $this->handle->addChild("event");
    $node->addAttribute("type", $type);
    $node->addChild("timestamp", date("Y-m-d H:i:s.u"));
    $node->addChild("message", $msg);
  }

}