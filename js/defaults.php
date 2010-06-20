<?php
$ajaxProvider = true;
//Sets JQuery Message Defaults
header("Content-Type: text/javascript");
header("Cache-control: max-age=86400, must-revalidate, public");
header("Expires: ".date("D, d M Y H:i:s T",time()+(3600*24)));
require_once dirname(__FILE__)."/../includes/vars.inc.php";
require_once dirname(__FILE__)."/../includes/Database.class.php";
$dbConn = db_factory();

if ($config->isNode('cache','jsDefaultsScript')) die($config->getNode('cache','jsDefaultsScript')); //Use cache if available

ob_start();
?>$('*').ajaxError(function (event, request, settings) {
    if (request.status == 404) {
        $('#dialog').html("<?php echo str_replace(array("\"","'","\n","/"), array("\\\"","&quot;","","&frasl;"),$config->getNode('messages','ajax404')); ?>");
    }
    else if (request.status == 500) {
    	$('#dialog').html("<?php echo str_replace(array("\"","'","\n","/"), array("\\\"","&quot;","","&frasl;"),$config->getNode('messages','ajax500')); ?>");
	}
	else if (request.status == 403) {
		$('#dialog').html("<?php echo str_replace(array("\"","'","\n","/"), array("\\\"","&quot;","","&frasl;"),$config->getNode('messages','ajax403')); ?>");
    } else {
    $('#dialog').html("<?php echo str_replace(array("\"","'","\n","/"), array("\\\"","&quot;","","&frasl;"),$config->getNode('messages','ajaxError')); ?>");
    }
    $('#dialog').append('<p>Requested Page: '+settings.url+'</p>');
    $('#dialog').dialog({buttons: {Close: function() {$(this).dialog('destroy');}}, title: 'Load Error', dialogClass: 'ui-state-error'});
});

function loadMsg(str) {
  return "<center><img src='<?php echo $config->getNode('paths','root')."/images/loading.gif"; ?>' /><br />"+str+"</center>";
}

$.validator.setDefaults({errorClass: "ui-state-error"});<?php

$config->setNode('cache','jsDefaultsScript',ob_get_flush());
?>