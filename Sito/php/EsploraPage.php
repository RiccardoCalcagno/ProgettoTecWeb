<?php

//require
require_once ("DBinterface");

use DB\DBinterface;

//prelevo Esplora.html
$html = file_get_contents('../otherHTMLs/Esplora.html');

if(isset($_SESSION["username"]))
{
    $html = str_replace("<input id=\"Accesso\" type=\"submit\" value=\"Accedi\">", "<input id=\"Accesso\" type=\"submit\" value=\"Esci\">", $html);
    $html = str_replace("<input id=\"Iscrizione\" type=\"submit\" value=\"Iscrizione\">", "<input id=\"Iscrizione\" type=\"submit\" value=\"Area Personale\">", $html);
}

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