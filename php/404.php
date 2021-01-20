<?php
require_once("GeneralPurpose.php");
require_once("banners.php");

$html = file_get_contents("..". DIRECTORY_SEPARATOR . "html". DIRECTORY_SEPARATOR . "404.html");
$html = setup_clear($html);

if((isset($_GET["Hamburger"])) && ($_GET["Hamburger"]=="yes")){
    $html = str_replace("class=\"hideForHamburger\" ", " ", $html);
    $html = str_replace("{RedirectHamburger}", "../php/404.php?Hamburger=no", $html);
}else{
    $html = str_replace("{RedirectHamburger}", "../php/404.php?Hamburger=yes", $html);
}

$html = addPossibleBanner($html, "404.php");

echo $html;

?>