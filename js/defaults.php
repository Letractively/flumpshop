<?php
$ajaxProvider = true;
//Sets JQuery Message Defaults
header("Content-Type: text/javascript");
require_once dirname(__FILE__)."/../preload.php";
?>
$('*').ajaxError(function (event, request, settings) {
    if (request.status == 404) {
        $('#dialog').html('<?php echo str_replace("'","\\'",$config->getNode('messages','ajax404')); ?>');
    }
    else if (request.status == 500) {
    	$('#dialog').html('<?php echo str_replace("'","\\'",$config->getNode('messages','ajax500')); ?>');
    } else {
    $('#dialog').html('<?php echo str_replace("'","\\'",$config->getNode('messages','ajaxError')); ?><br />Error Code: '+request.status);
    }
    $('#dialog').append('<p>Requested Page: '+settings.url);
    $('#dialog').dialog({buttons: {Close: function() {$(this).dialog('destroy');}}, title: 'Load Error', dialogClass: 'ui-state-error'});
});

function loadMsg(str) {
  return "<center><img src='<?php echo $config->getNode('paths','root')."/images/loading.gif"; ?>' /><br />"+str+"</center>";
}