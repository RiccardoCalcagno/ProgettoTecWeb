<?php
require_once("GeneralPurpose.php");
require_once("banners.php");

$html = file_get_contents("..". DIRECTORY_SEPARATOR . "html". DIRECTORY_SEPARATOR . "Info_da_footer.html");
$html = setup_clear($html);
$html = addPossibleBanner($html, "info_da_footer.php");

echo $html;


?>
