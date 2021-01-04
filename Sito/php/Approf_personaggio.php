<?php 
require_once("GeneralPurpose.php");
require_once("banners.php");

$html = file_get_contents("..". DIRECTORY_SEPARATOR . "otherHTMLs". DIRECTORY_SEPARATOR . "Approfondimenti" . DIRECTORY_SEPARATOR . "approfondimento_scheda_giocatore.html");
$html = setup_clear($html);
$html = addPossibleBanner($html, "Approf_personaggio.php");

echo $html;

?>