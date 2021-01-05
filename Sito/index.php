<?php 
require_once("php" . DIRECTORY_SEPARATOR . "GeneralPurpose.php");
require_once("php" . DIRECTORY_SEPARATOR . "banners.php");

$html = file_get_contents("otherHTMLs" . DIRECTORY_SEPARATOR . "Home.html");

$html = setup_clear($html);

$html = addPossibleBanner($html, "index.php");

echo $html;

?>