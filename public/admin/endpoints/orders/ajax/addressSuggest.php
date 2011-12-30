<?php

/**
*  Administrative AJAX Endpoint. Used to search for an address in the order screen's "Load Data..." dialog
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
*  @Name        : addressSuggest.php
*  @Version     : 1.0
*  @author		: Lloyd Wallis <flump@flump.me>
*  @copyright	: Copyright (c) 2010, Lloyd Wallis
*/

require_once "../../../../preload.php";

if (!acpusr_validate('can_create_orders')) die($config->getNode('messages','adminDenied'));

$term = str_replace("'","''",$_GET['term']);

$result = $dbConn->query("
SELECT id,name,address1,address2,address3,postcode,country FROM `customers`

WHERE (id='$term' OR name LIKE '%".$term."%' OR address1='".$term."' or address2 LIKE '%".$term."%' OR address3 LIKE '%".$term."%' OR postcode LIKE '%".$term."%' OR country LIKE '%".$term."%' OR email LIKE '%".$term."%')

AND (name!='' AND address1!='' AND address2!='' AND address3!='')

AND archive=0

LIMIT 8");

$array = array();

while ($row = $dbConn->fetch($result)) {
	$array[] = array($row['id'],$row['name'],$row['address1'],$row['address2'],$row['address3'],$row['postcode'],$row['country']);
}

if (empty($array)) $array[] = array(0,"No results found","","","","","");

echo json_encode($array);
?>