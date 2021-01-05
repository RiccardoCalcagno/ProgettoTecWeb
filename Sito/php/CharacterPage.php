<?php
require_once("DBinterface.php");
require_once("character.php");
require_once("GeneralPurpose.php");

if ( session_status() == PHP_SESSION_NONE ) {
    session_start();
    header("Location: login.php"); // ?
}
else if ( !isset($_SESSION['username']) ) {

    error("Errore ..."); // header("Location: login.php"); ?
}
else if ( !isset($_SESSION['character_id'] )) {

    error("Errore ..."); // header("Location: login.php"); ?
}
else {
    $html = file_get_contents(".." . DIRECTORY_SEPARATOR . "otherHTMLs" . DIRECTORY_SEPARATOR . "SchedaGiocatore.html");
    $html = setup($html);

    //$_SESSION['username'] = 'user';    // testing
    //$_SESSION['character_id'] = 47;   // testing


    $db = new DBinterface();
    $openConnection = $db->openConnection();

    if ($openConnection) {

        $character = $db->getCharactersById($_SESSION['character_id']);
        $db->closeConnection();

        if(isset($character)) {
            
            if ($character->get_author() == $_SESSION['username']) {
                $html = str_replace("<nameValue />", $character->get_name(), $html);
                $html = str_replace("<imgPath />", "../images/razze/" . strtolower($character->get_race()) . ".png", $html);
                $html = str_replace("<raceValue />", $character->get_race(), $html);
                $html = str_replace("<classValue />", $character->get_class(), $html);
                $html = str_replace("<backgroundValue />", $character->get_background(), $html);
                $html = str_replace("<alignmentValue />", $character->get_alignment(), $html);
                $html = str_replace("<traitsValue />", $character->get_traits(), $html);
                $html = str_replace("<idealsValue />", $character->get_ideals(), $html);
                $html = str_replace("<bondsValue />", $character->get_bonds(), $html);
                $html = str_replace("<flawsValue />", $character->get_flaws(), $html);
            }
            else {  // User sta cercando di accedere ad un personaggio non suo
                $html = "<h1 style=\"font-size:5em; text-align: center; color: red;\">WAITTHATSILLEGAL</h1>";
                // ERROR PAGE ?

            }
        }
        else {
        // Can't get data from DB
            // ERROR PAGE ? // (ERRORE LATO DB)
        }
    }
    else {
        // Non serve chiudere la connessione qui se non si e' neanche aperta (no ?)
    // Can't connect to DB
        // ERROR PAGE ? // (ERRORE LATO Server)
    }
    
    echo $html;
}

?>