<?php
require_once("GeneralPurpose.php");
require_once("banners.php");


if( session_status() == PHP_SESSION_NONE) {
    session_start();
}
else if ( !isset($_SESSION['errorMessage']) ) {
    $_SESSION['errorMessage'] = 'Errore: Nessun Errore :)';
}
else {
    $html = file_get_contents("..". DIRECTORY_SEPARATOR . "html". DIRECTORY_SEPARATOR . "Errore.html");
    $html = setup_clear($html);
    $html = addPossibleBanner($html, "Errore.php");
    $html = str_replace('<TestoErrore />', $_SESSION['errorMessage'], $html);
    
    echo $html;
    
}

?>
