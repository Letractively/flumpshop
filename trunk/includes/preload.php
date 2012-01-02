<?php
define('FS_VERSION', '0.9.9.594');
/**
 * Include the event handlers
 */
require_once dirname(__FILE__) . '/handlers.inc';

/**
 * @const PAGE_TYPE
 * Set by individual views, this tells plugins what content is currently being
 * displayed to the client
 */
if (!defined('PAGE_TYPE'))
  define('PAGE_TYPE', 'Unspecified');

/**
 * Wait for the page to finish before sending output - dirty workaround for
 * headers as well as enabling gzip compression
 */
ob_start('ob_gzhandler');
header('Content-Type: text/html;charset=UTF-8');

/**
 * @todo Make this a setup-defined parameter (timezone)
 */
ini_set('date.timezone', 'Europe/London');

/**
 * @var boolean $_SETUP When true, tells the internal system that the setup
 * wizard is still in progress, so not to die because something doesn't work
 * yet
 */
$_SETUP = false;

/**
 * @var boolean $ajaxProvider When true, the current request is for an ajax
 * view. Don't output any unneccessary content, the view itself should
 * handle any errors.
 */
if (!isset($ajaxProvider))
  $ajaxProvider = false;

/**
 * Load the $initcfg Configuration Store
 */
require_once(dirname(__FILE__) . '/../models/init_config.inc');
//Check the result of the $initcfg model
switch ($controller_action) {
  case 'runSetup':
    //There is no initcfg file. Run the setup wizard.
    require dirname(__FILE__) . '/../views/setup_go.inc';
    exit;
  case 'abort':
    //The configuration did not initialise correctly.
    require dirname(__FILE__) . '/../views/init_error.inc';
    exit;
  case 'continue':
    break;
  default:
    //I don't know how to deal with this.
    trigger_error('preload.php encountered an invalid response from init_config.inc. The response was '.$controller_action.'.');
}
unset($controller_action);

/**
 * Connect to the database
 */
require_once dirname(__FILE__) . '/Database.class.php';

$dbConn = db_factory($initcfg);
if ($dbConn->connect_error) {
  //Database connection failed.
  $error_description = 'Database error: ' . $dbConn->connect_error;
  require dirname(__FILE__) . '/../views/init_error.inc';
  exit;
}

require_once dirname(__FILE__) . '/../models/main_config.inc';
//Check the result of the $config model
switch ($controller_action) {
  case 'abort':
    //The configuration did not initialise correctly.
    require dirname(__FILE__) . '/../views/init_error.inc';
    exit;
  case 'continue':
    break;
  default:
    //I don't know how to deal with this.
    trigger_error('preload.php encountered an invalid response from main_config.inc. The response was '.$controller_action.'.');
}
unset($controller_action);

//Initialise the session if it is not already running
if (!isset($_SESSION)) {
  session_start();
}

//Maintenance Page
if ($_SETUP == false &&
        $config->getNode('site', 'enabled') != true &&
        !strstr($_SERVER['REQUEST_URI'], '/admin/')) {
  require dirname(__FILE__) . '/../views/error_maintenance.inc';
}

$stats = new Stats();

/* Actual User Initialisation */
if ($_SETUP === false) {
  $session = $dbConn->query('SELECT basket FROM `sessions` WHERE session_id="' . session_id() . '" LIMIT 1');
  if ($session === false) {
    trigger_error($dbConn->error());
  }
  if ($session->num_rows === 0) {
    //Build Session
    //Web Crawler Exception
    if (isset($_SERVER['HTTP_USER_AGENT'])
            && array_search($_SERVER['HTTP_USER_AGENT'], explode('|', $config->getNode('server', 'crawlerAgents')))) {
      define('CRAWLER', true);
      $basket = new Cart(0);
      $basket->lock();
    } else {
      define('CRAWLER', false);
      $basket = new Cart(-1);
    }
    if (!isset($_SERVER['REMOTE_ADDR']))
      $ip = '127.0.0.1'; else
      $ip = $_SERVER['REMOTE_ADDR'];
    $dbConn->query('INSERT INTO `sessions` (session_id,basket,ip_addr) VALUES ("' . session_id() . '","' . $basket->getID() . '","' . ip2long($_SERVER['REMOTE_ADDR']) . '")');
  } else {
    if (array_search($_SERVER['HTTP_USER_AGENT'], explode($config->getNode('server', 'crawlerAgents'), '|'))) {
      $basket = new Cart(0);
      $basket->lock();
      $config->setNode('temp', 'crawler', true);
    } else {
      $session = $dbConn->fetch($session);
      $basket = new Cart($session['basket']);
      $config->setNode('temp', 'crawler', false);
    }
  }
}

//Locale
if (isset($_SESSION['locale']) && !$_SETUP)
  $config->setNode('temp', 'country', $_SESSION['locale']);

//Theme Includer
function templateContent($id = -1) {
  global $config, $dbConn, $navigation_links, $page_title;
  if ($config->getNode('site', 'templateMode') === 'core') {
    $page_content = ob_get_clean(); //Place in template

    $file = templateFinder(PAGE_TYPE, $id);
    require $file;
  } else {
    ob_end_flush();
  }
}

function templateFinder($page_type, $id = -1) {
  //Returns template file to include
  global $config;
  $dir = $config->getNode('paths', 'offlineDir') . '/themes/core/' . $config->getNode('site', 'theme') . '/';
  if ($id !== -1 && file_exists($dir . $page_type . '.' . $id . '.content.tpl.php')) {
    return $dir . $page_type . '.' . $id . '.content.tpl.php';
  } elseif (file_exists($dir . $page_type . '.content.tpl.php')) {
    return $dir . $page_type . '.content.tpl.php';
  } else {
    return $dir . 'content.tpl.php';
  }
}

/* PLUGINS */
//Each plugin that has /includes/preload.inc.php will have an include here
$dir = opendir($config->getNode('paths', 'offlineDir') . "/plugins");
while ($module = readdir($dir)) {
  if (file_exists($config->getNode('paths', 'offlineDir') . "/plugins/" . $module . "/includes/preload.inc.php")) {
    include $config->getNode('paths', 'offlineDir') . "/plugins/" . $module . "/includes/preload.inc.php";
  }
}

//Admin Functions
//Validation
function get_valid_class($tree, $node) {
  $class = '';
  switch ($tree) {
    case 'paths':
      switch ($node) {
        case 'root':
          $class .= 'url ';
        case 'path':
          $class .= 'required ';
          break;
      }
      break;
  }
  return $class;
}

//Used for CMS pages
function placeholders($string) {
  global $config;
  $string = html_entity_decode(preg_replace_callback('/\[\[([a-z]*)?\]\]/i', 'placeholder_callback', $string));
  $string = str_replace('&apos;', '\'', $string);
  return $string;
}

function placeholder_callback($args) {
  global $config;
  return $config->getNode('messages', $args[1]);
}

/**
 * Decides whether to include the cron script in the footer of this page
 */
if (!$_SETUP) {
  if ($config->getNode('server', 'lastCron')
          < time() - ($config->getNode('server', 'cronFreq') * 60)
          && !$ajaxProvider) {
    //Actually ran in footer if necessary
    $cron = true;
  } else {
    $cron = false;
  }
}

require_once dirname(__FILE__) . '/acp.inc.php';