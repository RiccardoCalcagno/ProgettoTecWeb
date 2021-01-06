<?php 
require_once("php" . DIRECTORY_SEPARATOR . "GeneralPurpose.php");
require_once("php" . DIRECTORY_SEPARATOR . "banners.php");

$html = file_get_contents("html" . DIRECTORY_SEPARATOR . "Home.html");

$html = str_replace("<body>","<body> <h1>OOOOOOOOOOOOOO</h1>",$html);
$html = setup_clear($html);

$html = addPossibleBanner($html, "index.php");

echo $html;

?>