<?php
require_once("DBinterface.php");
require_once("GeneralPurpose.php");
require_once("character.php");
require_once("banners.php");

unset($_SESSION["first_logged"]);
unset($_SESSION["listaGiocatori"]);

function characterPage($charID) {

    $html = file_get_contents(".." . DIRECTORY_SEPARATOR . "html" . DIRECTORY_SEPARATOR . "SchedaGiocatore.html");
    $html = setup($html);

    $db = new DBinterface();
    $openConnection = $db->openConnection();

    if ($openConnection) {

        $character = $db->getCharactersById($charID);
        $db->closeConnection();

        if(isset($character)) {
            
            if ($character->get_author() == $_SESSION['username']) {
                $html = str_replace("<nameValue />", $character->get_name(), $html);
                $html = str_replace("<imgPath />", "../img/razze/" . strtolower($character->get_race()) . ".png", $html);
                $html = str_replace("<raceValue />", $character->get_race(), $html);
                $html = str_replace("<classValue />", $character->get_class(), $html);
                $html = str_replace("<backgroundValue />", $character->get_background(), $html);
                $html = str_replace("<alignmentValue />", $character->get_alignment(), $html);
                $html = str_replace("<traitsValue />", $character->get_traits(), $html);
                $html = str_replace("<idealsValue />", $character->get_ideals(), $html);
                $html = str_replace("<bondsValue />", $character->get_bonds(), $html);
                $html = str_replace("<flawsValue />", $character->get_flaws(), $html);
                $html = str_replace("<charIDValue />", $charID, $html);
            }
            else {  // User sta cercando di accedere ad un personaggio non suo
                errorPage("Questo personaggio non e' tuo ??? !! :))");  // ERROR PAGE ?
                
            }
        }
        else {
        // Can't get data from DB
            errorPage("Problema ?"); // ERROR PAGE ? // (ERRORE LATO DB)
        }
    }
    else {
        // Non serve chiudere la connessione qui se non si e' neanche aperta (no ?)
    // Can't connect to DB
        errorPage("Connessione con DB non riuscita.");  // ERROR PAGE ? // (ERRORE LATO Server)
    }

    $html = addPossibleBanner($html, "CharacterPage.php");

    return $html;
}

function changeCharLayout($html) {

    // Modifica bottoni
    $html = str_replace(
      '<button id="pergamena" class="disabled" type="submit" name="charLayout" value="pergamena" onclick="switchCharLayout(this)" disabled="disabled">PERGAMENA</button>', 
      '<button id="pergamena" class="active" type="submit" name="charLayout" value="pergamena" onclick="switchCharLayout(this)">PERGAMENA</button>', $html
    );
    $html = str_replace(
      '<button id="scheda" class="active" type="submit" name="charLayout" value="scheda" onclick="switchCharLayout(this)"><span xml:lang="en" lang="en">STANDARD</span> <abbr title="Dungeons & Dragons">D&D</abbr></button>',
      '<button id="scheda" class="disabled" type="submit" name="charLayout" value="scheda" onclick="switchCharLayout(this)" disabled="disabled"><span xml:lang="en" lang="en">STANDARD</span> <abbr title="Dungeons & Dragons">D&amp;D</abbr></button>', $html
    );

    // Modifica Layout
    $html = str_replace('id="contentPersonaggio" class="pergamena"', 'id="contentPersonaggio" class="scheda"', $html);

    return $html;
}

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