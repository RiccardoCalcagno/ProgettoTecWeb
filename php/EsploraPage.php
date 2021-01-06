<?php

//require
require_once("DBinterface.php");

//prelevo Esplora.html

$html = file_get_contents('..'. DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR . 'Esplora.html');
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

$html = addPossibleBanner($html, "EsploraPage.php");

echo $html;

?>