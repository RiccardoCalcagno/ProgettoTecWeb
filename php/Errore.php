<?php
require_once("GeneralPurpose.php");
require_once("banners.php");

$html = file_get_contents("..". DIRECTORY_SEPARATOR . "html". DIRECTORY_SEPARATOR . "Errore.html");
$html = setup_clear($html);

if((isset($_GET["Hamburger"])) && ($_GET["Hamburger"]=="yes")){
    $html = str_replace("class=\"hideForHamburger\" ", " ", $html);
    $html = str_replace("{RedirectHamburger}", "../php/Errore.php?Hamburger=no", $html);
}else{
    $html = str_replace("{RedirectHamburger}", "../php/Errore.php?Hamburger=yes", $html);
}

$html = addPossibleBanner($html, "Errore.php");
$html = str_replace('<TestoErrore />', $_SESSION['errorMessage'], $html);


echo $html;
?>
