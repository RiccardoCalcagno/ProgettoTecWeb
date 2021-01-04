<?php
require_once("GeneralPurpose.php");
require_once("banners.php");

$html = file_get_contents("..". DIRECTORY_SEPARATOR . "otherHTMLs". DIRECTORY_SEPARATOR . "404.html");
$html = setup_clear($html);
$html = addPossibleBanner($html, "404.php");

echo $html;

?>