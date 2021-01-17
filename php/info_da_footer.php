<?php
require_once("GeneralPurpose.php");
require_once("banners.php");


if( session_status() == PHP_SESSION_NONE) {
    session_start();
}
else if ( !isset($_SESSION['errorMessage']) ) {
    $_SESSION['errorMessage'] = 'Errore: Nessun Errore :)';
}

$html = file_get_contents("..". DIRECTORY_SEPARATOR . "html". DIRECTORY_SEPARATOR . "Info_da_footer.html");
$html = setup_clear($html);
$html = addPossibleBanner($html, "info_da_footer.php");

echo $html;


?>
