<?php
require_once("GeneralPurpose.php");
require_once("banners.php");

$html = file_get_contents("..". DIRECTORY_SEPARATOR . "html". DIRECTORY_SEPARATOR . "approfondimento_Report.html");
$html = setup_clear($html);

if((isset($_GET["Hamburger"])) && ($_GET["Hamburger"]=="yes")){
    $html = str_replace("class=\"hideForHamburger\" ", " ", $html);
    $html = str_replace("{RedirectHamburger}", "../php/Approf_report.php?Hamburger=no", $html);
}else{
    $html = str_replace("{RedirectHamburger}", "../php/Approf_report.php?Hamburger=yes", $html);
}

$html = addPossibleBanner($html, "Approf_report.php");

echo $html;

?>