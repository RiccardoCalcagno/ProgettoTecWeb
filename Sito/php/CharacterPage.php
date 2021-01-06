<?php
require_once("DBinterface.php");
require_once("character.php");
require_once("GeneralPurpose.php");
require_once("banners.php");

$_SESSION['username'] = 'user';
$_SESSION['character_id'] = 47;

function characterSheet($html = '') {

    $html = file_get_contents(".." . DIRECTORY_SEPARATOR . "otherHTMLs" . DIRECTORY_SEPARATOR . "SchedaGiocatore.html");
    $html = setup($html);

    //$_SESSION['username'] = 'user';    // testing
    //$_SESSION['character_id'] = 47;   // testing


    if(isset($_SESSION['documento'])){
        header("Location: CharacterPage.php");
        if($_SESSION['documento']=="ELIMINA"){

            $db = new DBinterface();
            $openConnection = $db->openConnection();
        
            if ($openConnection) {
                $result= $db->deleteCharacter($_SESSION['character_id']);
                if(isset($result)){
                    header("Location: area_personale.php");
                }else{
                    // Can't get data from DB
                    // ERROR PAGE ? // (ERRORE LATO DB)       
                }
            }else{
                // Can't get data from DB
                // ERROR PAGE ? // (ERRORE LATO DB)
            }
        }
        exit();
    }

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
                error("WAITTHATSILLEGAL");
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

    return $html;
}

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
else  {
    echo characterSheet();
}

$html = addPossibleBanner($html, "CharacterPage.php");

echo $html;

?>