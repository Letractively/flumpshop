$.validator.addMethod("unique", function(value,element,params) {
	//Flumpnet Custom Validation method - used for groups of selections to prevent the same item being selected twice
	if (value == "") return true; //Empty Entry
	if ($('.'+params+'[value="'+value+'"]:not("#'+element.id+'")').length == 0) return true; else return false;
}, "You've already chosen this value.");

$.validator.addMethod("checkOrderQuantity", function(value,element,params) {
	//Flumpnet Custom Validation method - Checks that an order doesn't include more items then are available
	if (value < 0) return false;
	return !(value > window.orderItemStock[params]);
}, $.validator.format("There is not enough stock for the selected quantity on row {0}."));

$.validator.addMethod("positiveInt", function(value,element) {
	return this.optional(element) || /^[0-9]*$/.test(value);
}, "Please enter a positive, whole number.");

$.validator.addMethod("positive", function(value,element) {
	return this.optional(element) || value > 0;
}, "Please enter a positive number.");

jQuery.validator.addMethod("maxWords", function(value, element, params) { 
    return this.optional(element) || value.match(/\b\w+\b/g).length < params; 
}, jQuery.validator.format("Please enter {0} words or less.")); 
 
jQuery.validator.addMethod("minWords", function(value, element, params) { 
    return this.optional(element) || value.match(/\b\w+\b/g).length >= params; 
}, jQuery.validator.format("Please enter at least {0} words.")); 
 
jQuery.validator.addMethod("rangeWords", function(value, element, params) { 
    return this.optional(element) || value.match(/\b\w+\b/g).length >= params[0] && value.match(/bw+b/g).length < params[1]; 
}, jQuery.validator.format("Please enter between {0} and {1} words."));

jQuery.validator.addMethod("letterswithbasicpunc", function(value, element) {
	return this.optional(element) || /^[a-z-.,()'\"\s]+$/i.test(value);
}, "Letters or punctuation only please");  

jQuery.validator.addMethod("alphanumeric", function(value, element) {
	return this.optional(element) || /^\w+$/i.test(value);
}, "Letters, numbers, spaces or underscores only please");  

jQuery.validator.addMethod("lettersonly", function(value, element) {
	return this.optional(element) || /^[a-z]+$/i.test(value);
}, "Letters only please"); 

jQuery.validator.addMethod("nowhitespace", function(value, element) {
	return this.optional(element) || /^\S+$/i.test(value);
}, "No white space please"); 

// NOTICE: Modified version of Castle.Components.Validator.CreditCardValidator
// Redistributed under the the Apache License 2.0 at http://www.apache.org/licenses/LICENSE-2.0
// Valid Types: mastercard, visa, amex, dinersclub, enroute, discover, jcb, unknown, all (overrides all other settings)
jQuery.validator.addMethod("creditcardtypes", function(value, element, param) {

	if (/[^0-9-]+/.test(value)) 
		return false;
	
	value = value.replace(/\D/g, "");
	
	var validTypes = 0x0000;
	
	if (param.mastercard) 
		validTypes |= 0x0001;
	if (param.visa) 
		validTypes |= 0x0002;
	if (param.amex) 
		validTypes |= 0x0004;
	if (param.dinersclub) 
		validTypes |= 0x0008;
	if (param.enroute) 
		validTypes |= 0x0010;
	if (param.discover) 
		validTypes |= 0x0020;
	if (param.jcb) 
		validTypes |= 0x0040;
	if (param.unknown) 
		validTypes |= 0x0080;
	if (param.all) 
		validTypes = 0x0001 | 0x0002 | 0x0004 | 0x0008 | 0x0010 | 0x0020 | 0x0040 | 0x0080;
	
	if (validTypes & 0x0001 && /^(51|52|53|54|55)/.test(value)) { //mastercard
		return value.length == 16;
	}
	if (validTypes & 0x0002 && /^(4)/.test(value)) { //visa
		return value.length == 16;
	}
	if (validTypes & 0x0004 && /^(34|37)/.test(value)) { //amex
		return value.length == 15;
	}
	if (validTypes & 0x0008 && /^(300|301|302|303|304|305|36|38)/.test(value)) { //dinersclub
		return value.length == 14;
	}
	if (validTypes & 0x0010 && /^(2014|2149)/.test(value)) { //enroute
		return value.length == 15;
	}
	if (validTypes & 0x0020 && /^(6011)/.test(value)) { //discover
		return value.length == 16;
	}
	if (validTypes & 0x0040 && /^(3)/.test(value)) { //jcb
		return value.length == 16;
	}
	if (validTypes & 0x0040 && /^(2131|1800)/.test(value)) { //jcb
		return value.length == 15;
	}
	if (validTypes & 0x0080) { //unknown
		return true;
	}
	return false;
}, "Please enter a valid credit card number.");
