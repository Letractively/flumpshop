<?php
/*
Flumpshop PDFTest for invoicing

Revision: 0
Date: 21/05/2010

*/

require "fpdf.php";
require "../includes/vars.inc.php";
require "../includes/Database.class.php";
require "../includes/Cart.class.php";
require "../includes/Item.class.php";
$dbConn = db_factory();

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
$pdf->Write(5,$_GET['orderID']);

$pdf->SetXY($pdf->lMargin,$pdf->rMargin);
$pdf->SetFont("Arial","B",12);
$pdf->Cell(130,5,strtoupper($config->getNode("messages","name")), 0, 0, "L", 0, 0);

$pdf->SetFontSize(16);
$pdf->Cell(50,10,"INVOICE", 1, 1, "C", 1, 0);


$pdf->SetY($pdf->GetY()-5); //Move Up a little

$pdf->SetFont("Arial","",10);
$pdf->MultiCell(130,4,$config->getNode("messages","address"));
//End Invoice Header

//Start Addresses
$pdf->Ln();
$pdf->Cell(1,10,"",0,2);

$pdf->Cell(90,5,"Billing Address: ",1,0,"L",1);
$pdf->Cell(90,5,"Shipping Address: ",1,1,"L",1);
$pdf->MultiCell(90,4,"Not Implemented",1);
$pdf->SetXY(100,$pdf->GetY()-28);
$pdf->MultiCell(90,4,"Not Implemented",1);
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
$pdf->Cell(30,5,$_GET['salesperson'],1,0,"C");
$pdf->Cell(30,5,$_GET['PONumber'],1,0,"C");
$pdf->Cell(30,5,$_GET['shippingDate'],1,0,"C");
$pdf->Cell(30,5,$_GET['shippingVia'],1,0,"C");
$pdf->Cell(30,5,$_GET['FOBPoint'],1,0,"C");
$pdf->Cell(30,5,$_GET['terms'],1,1,"C");
//End Order Info Table

//Start Order Items Table
$pdf->Cell(1,10,"",0,2);

// Header Row
$pdf->Cell(20,5,"Quantity",1,0,"C",1);
$pdf->Cell(120,5,"Description",1,0,"L",1);
$pdf->Cell(20,5,"Unit Price",1,0,"C",1);
$pdf->Cell(20,5,"Amount",1,1,"C",1);

// Content
//  Load the Order Details
$result = $dbConn->query("SELECT obj FROM basket WHERE id IN (SELECT basket FROM orders WHERE id='".intval($_GET['orderID'])."') LIMIT 1");

$row = $dbConn->fetch($result);
unset($result);

$basket = unserialize(base64_decode($row['obj']));
unset($row);

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