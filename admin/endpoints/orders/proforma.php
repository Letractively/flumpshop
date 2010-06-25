<?php

/**
*  Creates an unplaced order and builds a Proforma Invoice PDF
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
*  @Name        : proforma.php
*  @Version     : 1.0
*  @author		: Lloyd Wallis <lloyd@theflump.com>
*  @copyright	: Copyright (c) 2010, Lloyd Wallis
*/

//Minimalist load - predefined batch loaders may output data
if (!isset($_SESSION)) session_start();
require '../../../fpdf/fpdf.php';
require '../../../includes/vars.inc.php';
require '../../../includes/Database.class.php';
require '../../../includes/Customer.class.php';
require '../../../includes/Cart.class.php';
require '../../../includes/Order.class.php';
require '../../../includes/Item.class.php';
require '../../../includes/acp.inc.php';
$dbConn = db_factory();

acpusr_validate("CAN_CREATE_ORDERS") or die();

//Build the order from posted data
$order = Order::createFromOrderScreen();

//Load the Order Details

$basket = new Cart($order->basket);

$billingAddress = new Customer($order->billing);
$shippingAddress = new Customer($order->shipping);

$pdf = new FPDF("P","mm","A4"); //Portrait, mm measurement, A4 format

$pdf->AddPage(); //Create the page
$pdf->SetFillColor(200); //Shading Colour

//Invoice Header
// Do Date/Number first so that the cursor is in right position for content
$pdf->SetXY(140,20);
$pdf->Cell(25,10,'',1);
$pdf->Cell(25,10,'',1);

$pdf->SetFont("Arial","",8);
$pdf->SetXY(140,$pdf->GetY()+1);
$pdf->Write(2,"Date");

$pdf->SetX(165);
$pdf->Write(2,"Number");

$pdf->SetFontSize(10);
$pdf->SetXY(140,$pdf->GetY()+3);
$pdf->Write(5,date("d/m/Y"));

$pdf->SetX(165);
$pdf->Write(5,$order->getID());

$pdf->SetXY($pdf->lMargin,$pdf->rMargin);
$pdf->SetFont("Arial","B",12);
$pdf->Cell(130,5,strtoupper($config->getNode("messages","name")), 0, 0, "L", 0, 0);

$pdf->SetFontSize(16);
$pdf->Cell(50,10,"Proforma Invoice", 1, 1, "C", 1, 0);


$pdf->SetY($pdf->GetY()-5); //Move Up a little

$pdf->SetFont("Arial","",10);
$pdf->MultiCell(130,4,$config->getNode("messages","address"));
//End Invoice Header

//Start Addresses
$pdf->Ln();
$pdf->Cell(1,10,"",0,2);

$pdf->Cell(90,5,"Billing Address: ",1,0,"L",1);
$pdf->Cell(90,5,"Shipping Address: ",1,1,"L",1);
// Billing Address
$pdf->MultiCell(90,4,$billingAddress->getName()."\n".
					$billingAddress->getAddress1()."\n".
					$billingAddress->getAddress2()."\n".
					$billingAddress->getAddress3()."\n".
					$billingAddress->getPostcode()."\n".
					$billingAddress->getCountryName(),1);
$pdf->SetXY(100,$pdf->GetY()-24);
// Delivery Address
$pdf->MultiCell(90,4,$shippingAddress->getName()."\n".
					$shippingAddress->getAddress1()."\n".
					$shippingAddress->getAddress2()."\n".
					$shippingAddress->getAddress3()."\n".
					$shippingAddress->getPostcode()."\n".
					$shippingAddress->getCountryName(),1);
//End Addresses

//Start Order Info Table
$pdf->Cell(1,10,"",0,2);

// Header Row
$pdf->Cell(30,5,"Salesperson",1,0,"C",1);
$pdf->Cell(30,5,"P.O. No.",1,0,"C",1);
$pdf->Cell(30,5,"Date Shipped",1,0,"C",1);
$pdf->Cell(30,5,"Shipped Via",1,0,"C",1);
$pdf->Cell(30,5,"F.O.B. Point",1,0,"C",1);
$pdf->Cell(30,5,"Terms",1,1,"C",1);

// Data Row
$pdf->Cell(30,5,$acp_uname,1,0,"C");
$pdf->Cell(30,5,'N/A',1,0,"C");
$pdf->Cell(30,5,'N/A',1,0,"C");
$pdf->Cell(30,5,'Default Carrier',1,0,"C");
$pdf->Cell(30,5,'N/A',1,0,"C");
$pdf->Cell(30,5,'N/A',1,1,"C");
//End Order Info Table

//Start Order Items Table
$pdf->Cell(1,10,"",0,2);

// Header Row
$pdf->Cell(20,5,"Quantity",1,0,"C",1);
$pdf->Cell(120,5,"Description",1,0,"L",1);
$pdf->Cell(20,5,"Unit Price",1,0,"C",1);
$pdf->Cell(20,5,"Amount",1,1,"C",1);

// Content
$totalPrice = 0;
$totalDelivery = 0;
$currencySymbol = html_entity_decode("&pound;"); //Stops Browser outputting random characters

foreach (array_keys($basket->items) as $item) {
	$item = new Item($item);
	
	//Prices
	$itemTotal = $item->getPrice()*$basket->items[$item->getID()];
	$totalPrice += $itemTotal;
	$totalDelivery += $item->getDeliveryCost();
	
	$itemIdStr = strval($item->getID());
	while (strlen($itemIdStr) < 5) $itemIdStr = "0".$itemIdStr;
	
	//Output
	$pdf->Cell(20,4,$basket->items[$item->getID()],1,0,"C");
	$pdf->Cell(120,4,"(".$itemIdStr.") ".html_entity_decode($item->getName()),1,0,"L");
	$pdf->Cell(20,4,$item->getPrice(),1,0,"R");
	$pdf->Cell(20,4,number_format($itemTotal,2),1,1,"R");
}

// Blank Row
$pdf->Cell(20,4,"",1);
$pdf->Cell(120,4,"",1);
$pdf->Cell(20,4,"",1);
$pdf->Cell(20,4,"",1,1);

// Subtotal Row
$pdf->Cell(120,4,"",0);
$pdf->SetFont("","B");
$pdf->Cell(40,4,"Subtotal",1,0,"R");
$pdf->SetFont("","");
$pdf->Cell(20,4,number_format($totalPrice,2),1,1,"R");

// VAT
$salesTax = $totalPrice*($config->getNode("site","vat")/100);
$pdf->Cell(120,4,"",0);
$pdf->SetFont("","B");
$pdf->Cell(40,4,"VAT @".$config->getNode("site","vat")."%",1,0,"R");
$pdf->SetFont("","");
$pdf->Cell(20,4,number_format($salesTax,2),1,1,"R");

// Shipping & Handling
$pdf->Cell(120,4,"",0);
$pdf->SetFont("","B");
$pdf->Cell(40,4,"Shipping & Handling",1,0,"R");
$pdf->SetFont("","");
$pdf->Cell(20,4,number_format($totalDelivery,2),1,1,"R");

// Total
$total = $totalPrice+$salesTax+$totalDelivery;
$pdf->Cell(120,4,"",0);
$pdf->SetFont("","B");
$pdf->Cell(40,4,"Total",1,0,"R");
$pdf->Cell(20,4,$currencySymbol.number_format($total,2),1,1,"R");

//Footer
$pdf->SetY($pdf->GetY()+50);
$pdf->Cell(180,5,"Make All Checks Payable To:",0,1,"C");
$pdf->SetFont("","");
$pdf->Cell(180,5,$config->getNode("messages","name"),0,1,"C");
$pdf->SetFont("","B");
$pdf->Cell(180,5,"If you have any questions concerning this invoice, click the Contact link at",0,1,"C");
$pdf->SetFont("","");
$pdf->Cell(180,5,$config->getNode("paths","root"),0,1,"C",0,1,$config->getNode("paths","root"));
$pdf->SetFont("","B");
$pdf->Cell(180,5,"Thank you for your custom",0,1,"C");

$pdf->Output();
?>