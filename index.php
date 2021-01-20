<?php 
require_once("php" . DIRECTORY_SEPARATOR . "GeneralPurpose.php");
require_once("php" . DIRECTORY_SEPARATOR . "banners.php");

$html = file_get_contents("html" . DIRECTORY_SEPARATOR . "Home.html");

$html = setup_clear($html);
$html = str_replace('href="../php/InfoFooter.php"', 'href="php/InfoFooter.php"', $html);    // fix footer rel. path

if(isset($_GET["Hamburger"])){

    if($_GET["Hamburger"]=="yes"){
        $html = str_replace("<ul id=\"menu\"", "<ul id=\"menu\" style=\"display:block;\"", $html);
        $html = str_replace("../index.php?Hamburger=yes", "../index.php?Hamburger=no", $html);
    }
}

$html = addPossibleBanner($html, "index.php");

echo $html;

?>
