<?php
require_once("GeneralPurpose.php");
require_once("banners.php");

// ALTERNATIVA: usare $_SESSION['error'] = 'errorMessage'; e no funzione
function error($errorMessage) {

    $html = file_get_contents("..". DIRECTORY_SEPARATOR . "html". DIRECTORY_SEPARATOR . "Errore.html");
    $html = setup_clear($html);
    $html = addPossibleBanner($html, "Errore.php");
    $html = str_replace('<TestoErrore />', $errorMessage, $html);
    
    echo $html;

    exit();
}


?>
