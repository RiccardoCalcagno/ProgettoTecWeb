<?php 
require_once("php" . DIRECTORY_SEPARATOR . "GeneralPurpose.php");

$html = file_get_contents("Home.html");

$html = setup($html);

$html = addPossibleBanner($html, "../index.php");

echo $html;

?>