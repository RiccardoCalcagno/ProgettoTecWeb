<?php 
require_once("GeneralPurpose.php");

$html = file_get_contents("..". DIRECTORY_SEPARATOR . "html". DIRECTORY_SEPARATOR . "Info_da_footer.html");
$html = setup_clear($html);

if((isset($_GET["Hamburger"])) && ($_GET["Hamburger"]=="yes")){
    $html = str_replace("class=\"hideForHamburger\" ", " ", $html);
    $html = str_replace("{RedirectHamburger}", "../php/InfoFooter.php?Hamburger=no", $html);
}else{
    $html = str_replace("{RedirectHamburger}", "../php/InfoFooter.php?Hamburger=yes", $html);
}


echo $html;

?>