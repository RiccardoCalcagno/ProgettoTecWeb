<?php
require_once("GeneralPurpose.php");
require_once("CharacterPage.php");

if( session_status() == PHP_SESSION_NONE ) {
    session_start();
}

// GeneralPourpuse ?
// if ( session_status() == PHP_SESSION_NONE ) {
//     session_start();
//     header("Location: login.php"); // ?
// }
// else if ( !isset($_SESSION['username']) ) {

//     error("Errore ..."); // header("Location: login.php"); ?
// }
// else if ( !isset($_SESSION['character_id'] )) {

//     error("Errore ..."); // header("Location: login.php"); ?
// }

if ( !isset($_SESSION['charLayout']) ) {
    error('is this even possible');
}
else {
    
    $html = characterSheet();
    $html = str_replace('class="active"', 'class="TEMP"', $html);
    $html = str_replace('class="disabled"', 'class="active"', $html);
    $html = str_replace('class="TEMP"', 'class="disabled"', $html);

//disabled button

    $html = $_SESSION['charLayout'] == 'scheda' ?
        str_replace('id="contentPersonaggio" class="pergamena"', 'id="contentPersonaggio" class="scheda"', $html) :
        str_replace('id="contentPersonaggio" class="scheda"', 'id="contentPersonaggio" class="pergamena"', $html);

    echo $html;
}

?>