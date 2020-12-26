<?php

//require
require_once ("DBinterface");

use DB/DBinterface;

//prelevo Esplora.html
$html = file_get_contents('../otherHTMLs/Esplora.html');

$dbInterface = new DBinterface();
$connection = $dbInterface->openConnection();

if($connection == false){
	//redirecting to 404
	$dbInterface->closeConnection();
}
else{
	//sostituire il placeholder delle card di report, gestire le pagine di cards
}
?>