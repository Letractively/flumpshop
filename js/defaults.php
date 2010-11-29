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
  return "<center><img src='<?php echo $config->getNode('paths','root')."/images/loading.gif"; ?>' alt='<?php echo $config->getNode('messages','loading');?>' /><br />"+str+"</center>";
}

$.validator.setDefaults({errorClass: "ui-state-error"});

$.validator.addMethod("unique", function(value,element,params) {
	//Flumpnet Custom Validation method - used for groups of selections to prevent the same item being selected twice
	if (value == "") return true; //Empty Entry
	if ($('.'+params+'[value="'+value+'"]:not("#'+element.id+'")').length == 0) return true; else return false;
}, "You've already chosen this value.");

$.validator.addMethod("postcode", function(value,element) {
  return this.optional(element) || (/^([A-PR-UWYZ][A-HK-Y0-9][A-HJKSTUW0-9]?[ABEHMNPRVWXY0-9]?) ?[0-9][ABD-HJLN-UW-Z]{2}$/.test(value) || /^([0-9]{5}-[0-9]{4})|([0-9]{5})$/.test(value));
  }, "Please specify a valid postcode (all letters should be
uppercase)");

$.validator.addMethod("checkOrderQuantity", function(value,element,params) {
	//Flumpnet Custom Validation method - Checks that an order doesn't include more items then are available
	if (value < 0) return false;
	return !(value > window.orderItemStock[params]);
}, $.validator.format("There is not enough stock for the selected quantity on row {0}."));

$.validator.addMethod("positiveInt", function(value,element) {
	return this.optional(element) || /^[0-9]*(\.00)?$/.test(value);
}, "Please enter a positive, whole number.");

$.validator.addMethod("positive", function(value,element) {
	return this.optional(element) || value > 0;
}, "Please enter a positive number.");<?php

$output = ob_get_clean();

//Compress
$output = preg_replace('/[^:]\/\/.*?\\n/','',$output); //Comments (: prevents clearing http://, ftp:// etc.
$output = str_replace(array("\t","\n"),'',$output); //Whitespace
$output = str_replace('  ',' ',$output); //Double Spaces

echo $output;

$config->setNode('cache','jsDefaultsScript',$output);
?>