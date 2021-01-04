<?php

//require
require_once("DBinterface");

//prelevo Esplora.html
$html = file_get_contents('../otherHTMLs/Esplora.html');
$html = setup($html);

$dbInterface = new DBinterface();
$connection = $dbInterface->openConnection();

if($connection == false){
    //redirecting to 404
    $dbInterface->closeConnection();
}
else{
    //sostituire il placeholder delle card di report, gestire le pagine di cards
}

echo $html;

?>