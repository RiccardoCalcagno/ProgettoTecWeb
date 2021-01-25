<?php 
require_once("php" . DIRECTORY_SEPARATOR . "GeneralPurpose.php");
require_once("php" . DIRECTORY_SEPARATOR . "banners.php");

$html = file_get_contents("html" . DIRECTORY_SEPARATOR . "Home.html");

$html = setup_clear($html);
$html = str_replace('href="../php/InfoFooter.php"', 'href="php/InfoFooter.php"', $html);

if((isset($_GET["Hamburger"])) && ($_GET["Hamburger"]=="yes")){
        $html = str_replace("class=\"hideForHamburger\" ", " ", $html);
        $html = str_replace("{RedirectHamburger}", "index.php?Hamburger=no", $html);
    }else{
        $html = str_replace("{RedirectHamburger}", "index.php?Hamburger=yes", $html);
    }

$html = addPossibleBanner($html, "index.php");

echo $html;

?>
