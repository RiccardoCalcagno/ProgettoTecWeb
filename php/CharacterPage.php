<?php
require_once("GeneralPurpose.php");
require_once("CharPagesUtil.php");

if ( session_status() == PHP_SESSION_NONE ) {

    session_start();
    header("Location: login.php"); // ?
}
else if ( !isset($_SESSION['username']) ) {

    errorPage("Errore ..."); // header("Location: login.php"); ?
}
else if ( isset($_GET['Personaggio']) || isset($_GET['charID'])) { //|| isset($_SESSION['charLayoutID']) || isset($_SESSION['character_id']) 

    $charID = isset($_GET['Personaggio']) ?
        $_GET['Personaggio'] :          // Da AreaPersonale
        $_GET['charID'];                // No JS -> PHP change layout

    $html = characterPage($charID);

    if ( isset($_GET['charLayout'])  && $_GET['charLayout'] == 'scheda' ) {
        $html = changeCharLayout($html);
    }
    
    echo $html;
}

?>