<?php
require_once("GeneralPurpose.php");
require_once("banners.php");

$html = file_get_contents("..". DIRECTORY_SEPARATOR . "otherHTMLs". DIRECTORY_SEPARATOR . "Errore.html");
$html = setup_clear($html);
$html = addPossibleBanner($html, "Errore.php");

echo $html;

?>