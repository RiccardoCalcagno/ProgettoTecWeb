<?php 
require_once("php" . DIRECTORY_SEPARATOR . "GeneralPurpose.php");
require_once("php" . DIRECTORY_SEPARATOR . "banners.php");

$html = file_get_contents("html" . DIRECTORY_SEPARATOR . "Home.html");

$html = setup_clear($html);
$html = str_replace('href="../php/InfoFooter.php"', 'href="php/InfoFooter.php"', $html);    // fix footer rel. path

$html = addPossibleBanner($html, "index.php");

echo $html;

?>
