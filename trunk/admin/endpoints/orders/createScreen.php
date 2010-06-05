<?php
$noPreValidate = true;
$USR_REQUIREMENT = 'can_create_orders';
require_once "../header.php";
?>
<style>
input:focus {
	color: #FFF;
	font-weight:bolder
}
.ui-autocomplete{width:500px!important}
</style>
<h1>Create an Order</h1>
<div id="errors" class="ui-state-highlight" style="display:none">
<span class='ui-icon ui-icon-alert'></span><strong>I found the following issues with the order:</strong><br />
<ul id="errorContent">

</ul>
</div>
<form action="javascript:" id="orderFormMain">
<table style="width:100%">
	<tr>
		<td>
			<fieldset>
				<legend>Order Details</legend>
				<div id='orderItemsContainer'>
					Preparing the Flumpshop Ordering Platform...
				</div>
			</fieldset>
			<fieldset>
				<legend>Other Details</legend>
				<table id="otherDetailsTable">
					<tr>
						<td><input type="checkbox" name="vatExempt" id="vatExempt" onclick="updatePrices()" /></td>
						<td colspan="3"><label for="vatExempt">This customer does not need to pay VAT</label></td>
					</tr>
					<tr>
						<td colspan="4"><a href="javascript:" onclick="addCouponCode();">Add a voucher to this order...</a>
					</tr>
				</table>
			</fieldset>
		</td>
		<td><fieldset>
			<legend>Billing Details [<a href='javascript:' onclick='loadBilling();'>Load Data...</a>]</legend>
			<table>
				<tr>
					<td><label for="customerID">Customer Number: </label></td>
					<td><input type='text' class='ui-state-disabled' disabled="disabled" value="New" name="customerID" id="customerID" /></td>
				</tr>
				<tr>
					<td><label for="customerBillingName">Customer Name: </label></td>
					<td><input type="text" class="ui-state-active required" name="customerBillingName" id="customerBillingName" /></td>
				</tr>
				<tr>
					<td><label for="customerBillingAddress1">Billing Address 1: </label></td>
					<td><input type="text" class="ui-state-active required" name="customerBillingAddress1" id="customerBillingAddress1" /></td>
				</tr>
				<tr>
					<td><label for="customerBillingAddress2">Billing Address 2: </label></td>
					<td><input type="text" class="ui-state-active" name="customerBillingAddress2" id="customerBillingAddress2" /></td>
				</tr>
				<tr>
					<td><label for="customerBillingAddress3">Billing Address 3: </label></td>
					<td><input type="text" class="ui-state-active" name="customerBillingAddress3" id="customerBillingAddress3" /></td>
				</tr>
				<tr>
					<td><label for="customerBillingPostcode">Billing Address Postcode: </label></td>
					<td><input type="text" class="ui-state-active required" name="customerBillingPostcode" id="customerBillingPostcode" /></td>
				</tr>
			</table>
			</fieldset>
			<fieldset>
			<legend>Shipping Details [<a href='javascript:' onclick='loadShipping();'>Load Data...</a>]</legend>
			<table>
				<tr>
					<td><label for="noShipping">Same as Billing: </label></td>
					<td><input type="checkbox" name="noShipping" id="noShipping" style="width:auto" class="ui-state-default" onchange="toggleShippingFields();" /></td>
				</tr>
				<tr>
					<td><label for="shippingID">Customer Number: </label></td>
					<td><input type='text' class='ui-state-disabled' disabled="disabled" value="New" name="shippingID" id="shippingID" /></td>
				</tr>
				<tr>
					<td><label for="customerShippingName">Customer Name: </label></td>
					<td><input type="text" class="ui-state-active required" name="customerShippingName" id="customerShippingName" /></td>
				</tr>
				<tr>
					<td><label for="customerShippingAddress1">Shipping Address 1: </label></td>
					<td><input type="text" class="ui-state-active required" name="customerShippingAddress1" id="customerShippingAddress1" /></td>
				</tr>
				<tr>
					<td><label for="customerShippingAddress2">Shipping Address 2: </label></td>
					<td><input type="text" class="ui-state-active" name="customerShippingAddress2" id="customerShippingAddress2" /></td>
				</tr>
				<tr>
					<td><label for="customerShippingAddress3">Shipping Address 3: </label></td>
					<td><input type="text" class="ui-state-active" name="customerShippingAddress3" id="customerShippingAddress3" /></td>
				</tr>
				<tr>
					<td><label for="customerShippingPostcode">Shipping Address Postcode: </label></td>
					<td><input type="text" class="ui-state-active required" name="customerShippingPostcode" id="customerShippingPostcode" /></td>
				</tr>
			</table>
			</fieldset>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<button onclick="buildProforma();">Generate a Proforma Invoice</button>
			<input type="submit" value="Confirm Order" />
		</td>
	</tr>
</table>
</form>
<script type="text/javascript">
/*Declare Variables*/
window.nextOrderItemID = 1;
window.prices = new Array();
window.orderItemStock = new Array();
window.itemDeliveryCosts = new Array();
window.nextCouponCodeID = 1;
window.couponActions = new Array();

/*Validation Rules*/
var validObject = {
	errorContainer:'#errors',
	errorLabelContainer:'#errorContent',
	wrapper:'li',
	errorClass:'errorDetails',
	messages:{
		customerBillingName: "Please enter the Customer Name in the billing address section.",
		customerBillingAddress1: "Please fill in Billing Address 1.",
		customerBillingPostcode: "Please fill in the Billing Address Postcode.",
		customerShippingName: "Please enter the Customer Name in the shipping address section.",
		customerShippingAddress1: "Please fill in Shipping Address 1.",
		customerShippingPostcode: "Please fill in the Shipping Address Postcode.",
		item1ID:{
			required:"Please enter at least one item in the order details section."
		},
	},
	rules:{
		item1ID:"required"
	}
};

//Create the order form
$(document).ready(function() {
	$('td').each(function() {$(this).css('vertical-align','top')});
	$('#orderItemsContainer').html("<table id='orderItemsTable'><tr><th>&nbsp;</th><th>Product Code</th><th>Product Name</th><th>Quantity</th><th>Price</th></tr></table>");
	
	$('#orderItemsContainer').append("<table id='itemsSummaryTable' style='width:100%;text-align:right'></table>");
	$('#itemsSummaryTable').append("<tr><th>Subtotal</th><td style='width:80px' id='subTotal'>£0.00</td><td rowspan='500' style='width:85px'></td></tr>");
	$('#itemsSummaryTable').append("<tr id='discountRow' style='display:none'><th>Discounts</th><td id='discounts'>£0.00</td></tr>");
	$('#itemsSummaryTable').append("<tr id='vatRow'><th>VAT @ <?php echo $config->getNode('site','vat');?>%</th><td id='vat'>£0.00</td></tr>");
	$('#itemsSummaryTable').append("<tr><th>Shipping & Handling</th><td id='shipping'>£0.00</td></tr>");
	$('#itemsSummaryTable').append("<tr><th>Total</th><td id='total'>£0.00</td></tr>");
	
	newOrderRow();
	$('#orderFormMain').validate(validObject);
	
	//Sets up custom messages (dirty I know, but couldn't get it to work any other way)
	$('#errorContent').bind("DOMSubtreeModified",function() {
		$('#errorContent li label').each(function (index,element) {
			var regex = new RegExp(/item[0-9]+ID/);
			if (regex.test($(this).attr("for")) && $(this).html() == "Please enter a valid number.") {
				//Product code not a number
				$(this).html("The product code on row "+$(this).attr("for").replace("item","").replace("ID","")+" is incorrect. Please enter a number.")
			}
			var regex = new RegExp(/item[0-9]+Qty/);
			
			if (regex.test($(this).attr("for")) && $(this).html() == "Please enter a valid number.") {
				//Not enough Stock
				$(this).html("Please enter a number for the quantity field on row "+$(this).attr("for").replace("item","").replace("Qty","")+".")
			} else if (regex.test($(this).attr("for")) && $(this).html() == "<?php echo str_replace('"','\"',$config->getNode('messages','formFieldRequired'));?>") {
				//No quantity entered
				$(this).html("Please enter a quantity on row "+$(this).attr("for").replace("item","").replace("Qty","")+".")
			}
		});
	});
});

//Adds a row to the order items
function newOrderRow() {
	newID = window.nextOrderItemID;
	
	newRow = "<tr id='orderRow"+newID+"'>";
	newRow = newRow+"<td><strong>"+newID+"</strong></td>";
	newRow = newRow+"<td><input type='text' name='item"+newID+"ID' id='item"+newID+"ID' class='ui-state-default number itemIDField' onkeyup='idKeyPress(this.id);' style='width:100px' unique='itemIDField' maxlength='11' /></td>";
	
	newRow = newRow+"<td><input type='text' disabled='disabled' class='orderItemName' id='item"+newID+"Name' style='width:300px;color:#000' /></td>";
	
	newRow = newRow+"<td><input type='text' name='item"+newID+"Qty' id='item"+newID+"Qty' class='ui-state-default number' onkeyup='quantityKeyPress(this.id);' onkeypress='document.lastQty = this.value;' style='width:65px' value='1' checkOrderQuantity='"+newID+"' required='$(\"item"+newID+"ID\").html() == \"\"' /></td>";
	
	newRow = newRow+"<td><input type='text' disabled='disabled' id='item"+newID+"Price' style='width:80px;' /></td>";
	
	newRow = newRow+"<td><a href='javascript:' onclick='findItem("+newID+");'>Search...</a></td>";
	
	newRow = newRow+"</tr>";
	
	$('#orderItemsTable').append(newRow);
	$('#orderRow'+newID).effect('highlight',{},3000);
	
	window.nextOrderItemID++;
}

function idKeyPress(id) {	
	idNumber = parseInt(id.replace("item","").replace("ID",""));
	
	if (idNumber+1 == window.nextOrderItemID) {
		newOrderRow();
	}
	
	if ($('#'+id).val() == "") return;
	
	//Find name
	$('#item'+idNumber+'Name, #item'+idNumber+'Price').val('     Checking...').css('background','url("../../../images/loading.gif") no-repeat');
	$.ajax({
		url:'../orders/ajax/itemName.php?id='+$('#'+id).val(),
		dataType:'json',
		success:function(data) {
			$('#item'+idNumber+'Name').val(data[0]).css('background','none');
			$('#item'+idNumber+'Price').val("£"+(data[1]*$('#item'+idNumber+'Qty').val()).toFixed(2)).css('background','none');
			window.prices[idNumber] = data[1];
			window.orderItemStock[idNumber] = data[2];
			window.itemDeliveryCosts[idNumber] = data[3];
			updatePrices();
		},
		cache:true
		});
}

function quantityKeyPress(id) {
	idNumber = parseInt(id.replace("item","").replace("Qty",""));
	
	if (idNumber+1 == window.nextOrderItemID) {
		newOrderRow();
	}
	
	originalPrice = window.prices[idNumber];
	
	if (parseInt($('#'+id).val()) <= 0) newVal = "0.00"; else
	newVal = (originalPrice*$('#'+id).val()).toFixed(2);
	
	$('#item'+idNumber+'Price').val("£"+newVal);
	window.lastQty = 0;
	
	updatePrices();
}

function loadCustomer() {
	loader('Not Implemented.');
}

function findItem(id) {
	window.tempFindItemId = id;
	$('#dialog').html("<img src='../../../images/loading.gif' />Loading Content...").attr('title','Find an Item');
	$('#dialog').dialog({width:600,height:400});
	$('#dialog').load('../orders/ajax/findItem.php?id='+id, function(var1,var2,var3) {
		//Initialise the new form
		$('#dialog form').validate();
		$('#findItemName').autocomplete({
			source:'../orders/ajax/itemSuggest.php',
			select: function(event, ui) {
				$('#findItemName').val(ui.item[1]);
				itemSummary(ui.item[0]);
				return false;
			}
		})
		.data("autocomplete")._renderItem = function(ul, item) {
			return $( "<li></li>")
				.data("item.autocomplete", item)
				.append("<a>"+item[1]+"</a>")
				.appendTo(ul);
		}
	});
}

function itemSummary(id) {
	$('#findItemDetails').html("<img src='../../../images/loading.gif' />Loading Content...")
		.load("../orders/ajax/itemSummary.php?id="+id);
}

function updatePrices() {
	//Updates the totals
	var total = 0;
	
	for (n=1;n<window.nextOrderItemID;n++) {
		//Calculate Total Price
		if ($('#item'+n+'Price').val() != "") {
			total += parseFloat($('#item'+n+'Price').val().replace('£',''));
		}
	}
	$('#subTotal').html('&pound;'+(total.toFixed(2)));
	
	var shipping = 0;
	for (n=1;n<window.nextOrderItemID;n++) {
		//Calculate Total Delivery
		if ($('#item'+n+'ID').val() != "") {
			shipping += parseFloat(window.itemDeliveryCosts[n]*$('#item'+n+'Qty').val());
		}
	}
	$('#shipping').html('&pound;'+(shipping.toFixed(2)));
	
	//Calculate Vouchers
	var totalAdjustment = 0;
	for (n=1;n<window.nextCouponCodeID;n++) {
		var action = window.couponActions[n];
		//Affects Basket Final Total
		if (action.match(/BasketTotal_/) !== null) {
			action = action.replace("BasketTotal_","");
			if (action.match(/[0-9\-]%/) !== null) {
				adjustment = parseFloat(action.replace("%",""))/100;
				adjustment = total*adjustment;
				$('#coupon'+n+'PriceAdjust').val(('£'+adjustment.toFixed(2)).replace("£-","-£"));
				totalAdjustment += adjustment;
			}
		}
		//Affects the price of a single item
		else if (action.match(/Item[0-9]*Price_/) !== null) {
			var item_id = action.replace(/Item/,"").replace(/Price_.*/,"");
			var newPrice = parseFloat(action.replace(/Item[0-9]*Price_/,""));
			//Find the item ID if its set, and adjust price based on quantity if so
			for (i=1;i<window.nextOrderItemID;i++) {
				if ($('#item'+i+'ID').val() == item_id) {
					//Found the item
					var oldPrice = parseFloat($('#item'+i+'Price').val().replace('£',''));
					var adjustment = (newPrice*$('#item'+i+'Qty').val())-oldPrice;
					
					$('#coupon'+n+'PriceAdjust').val(('£'+adjustment.toFixed(2)).replace("£-","-£"));
					totalAdjustment += adjustment;
					//Don't need to do anything with VAT - it's calculated afterwards anyway
				}
			}
		}
	}
	//Finalize Coupons
	$('#discounts').html(('&pound;'+totalAdjustment.toFixed(2)).replace("&pound;-","-&pound;"));
	if (totalAdjustment == 0 && $('#discountRow:visible').length != 0) {
		$('#discountRow').hide('highlight');
	} else if (totalAdjustment != 0 && $('#discountRow:visible').length == 0) {
		$('#discountRow').show('highlight');
	}
	total+=totalAdjustment;
	
	//Calculate VAT
	if ($('#vatExempt:checked').val() == null) {
		var vatRate = <?php echo $config->getNode('site','vat')/100;?>;
		if ($('#vatRow:visible').length == 0) $('#vatRow').show('highlight');
	} else {
		var vatRate = 0;
		if ($('#vatRow:visible').length == 1) $('#vatRow').hide('highlight');
	}
	$('#vat').html('&pound;'+(total*vatRate).toFixed(2));
	
	total = total*(1+vatRate);
	
	//Add shipping afterwards so VAT doesn't affect it
	total += shipping;
	
	$('#total').html('&pound;'+total.toFixed(2));
}

function addCouponCode() {
	newID = window.nextCouponCodeID;
	
	newRow = "<tr id='couponRow"+newID+"'>";
	newRow = newRow+"<td><strong>"+newID+"</strong></td>";
	newRow = newRow+"<td><input type='text' name='coupon"+newID+"Key' id='coupon"+newID+"Key' class='ui-state-default couponKey' onkeyup='couponKeyPress(this.id);' style='width:100px' unique='couponKey' maxlength='32' /></td>";
	
	newRow = newRow+"<td><input type='text' disabled='disabled' class='couponDetails' id='coupon"+newID+"Action' style='width:300px;color:#000' /></td>";
	
	newRow = newRow+"<td><input type='text' disabled='disabled' id='coupon"+newID+"PriceAdjust' style='width:80px;' /></td>";
	
	newRow = newRow+"</tr>";
	
	$('#otherDetailsTable').append(newRow);
	$('#couponRow'+newID).effect('highlight',{},3000);
	
	window.nextCouponCodeID++;
}

function couponKeyPress(id) {
	idNumber = parseInt(id.replace("coupon","").replace("Key",""));
	
	if ($('#'+id).val() == "") return;
	
	//Find name
	$('#coupon'+idNumber+'Action, #coupon'+idNumber+'PriceAdjust').val('     Checking...').css('background','url("../../../images/loading.gif") no-repeat');
	$.ajax({
		url:'../orders/ajax/couponAction.php?id='+$('#'+id).val(),
		dataType:'json',
		success:function(data) {
			$('#coupon'+idNumber+'Action').val(data[0]).css('background','none');
			
			//Calculate the effect the coupon has on the total
			window.couponActions[idNumber] = data[1];
			$('#coupon'+idNumber+'PriceAdjust').css('background','none');
			updatePrices();
		},
		cache:true
		});
}

$('#dialog').css('display','block');
</script></body></html>