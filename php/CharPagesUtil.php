<?php
require_once("DBinterface.php");
require_once("character.php");
require_once("GeneralPurpose.php");
require_once("banners.php");

// ------------------------------------------------- CharacterPage ----------------------------------------------------------
// --------------------------------------------------------------------------------------------------------------------------

function characterPage($charID) {

    $html = file_get_contents(".." . DIRECTORY_SEPARATOR . "html" . DIRECTORY_SEPARATOR . "SchedaGiocatore.html");
    $html = setup($html);

    //$_SESSION['username'] = 'user';    // testing
    //$_SESSION['character_id'] = 47;   // testing


 /*   if(isset($_SESSION['documento'])){
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
    }*/

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
      '<button id="scheda" class="active" type="submit" name="charLayout" value="scheda" onclick="switchCharLayout(this)">STANDARD D&D</button>',
      '<button id="scheda" class="disabled" type="submit" name="charLayout" value="scheda" onclick="switchCharLayout(this)" disabled="disabled">STANDARD D&amp;D</button>', $html
    );

    // Modifica Layout
    $html = str_replace('id="contentPersonaggio" class="pergamena"', 'id="contentPersonaggio" class="scheda"', $html);

    return $html;
}

// ------------------------------------------------- Character Form ---------------------------------------------------------
// --------------------------------------------------------------------------------------------------------------------------
 function checkText($text) {
    return preg_match("/^.{10,}$/", $text); // clean_input dopo
}

function preparePage($htmlPage, $toEdit) {

    $title = ''; $header = ''; $p = ''; $button = ''; $hiddenCharID = '';

    if ( $toEdit ){

        $title = '<title> - Modifica Scheda Giocatore</title>
        <meta name="title" content="Modifica Scheda Giocatore" />
        <meta name="description" content="Modifica la tua Scheda Giocatore!" />
        <meta name="keywords" content="modifica personaggio, Dungeons and Dragons character" />';

        $header = '<header id="intestazionePagina">
            <h1>Modifica Scheda Giocatore<span> <a class="puntoInterrogativo" href="../php/Approf_personaggio.php">?</a></span></h1>
            </header>';

            $p = 'Concludi la modifica salvando la nuova versione della Scheda Giocatore';

            $button = 'SALVA MODIFICA';

            $hiddenCharID = '<input type="hidden" id="charID" name="charID" value="'.$_GET['charID'].'" />';     // toEdit => get 
    }
    else  {

        $title = '<title> - Creazione Scheda Giocatore</title>
            <meta name="title" content="Creazione Scheda Giocatore" />
            <meta name="description" content="Crea la tua Scheda Giocatore!" />
            <meta name="keywords" content="creazione personaggio, Dungeons and Dragons character" />';

        $header = '<header id="intestazionePagina">
            <h1>Creazione Scheda Giocatore<span> <a class="puntoInterrogativo" href="../php/Approf_personaggio.php">?</a></span></h1>
            <p> Sei qui per realizzare la tua prima scheda giocatore? Non temere, è facilissimo! Compila questo ridotto insieme di campi per conferire al tuo personaggio le principali caratteristiche.</p>
            <p class="attenzioneP">(<strong class="Attenzione">Attenzione</strong>: per effettuare il salvataggio della scheda sarà necessaria una tua autenticazione)</p>
            </header>';

            $p = 'Concludi la crazione salvando nuova la Scheda Giocatore nella tua Area Personale';

            $button = 'SALVA SCHEDA'; 

            // $hiddenCharID = '';
    }
    // else {
    //     // ERROR PAGE ? forse non serve neanche 
    // }

    $htmlPage = str_replace('<titleValue />', $title, $htmlPage);
    $htmlPage = str_replace('<headerValue />', $header, $htmlPage);
    $htmlPage = str_replace('<pValue />', $p, $htmlPage);
    $htmlPage = str_replace('<buttonValue />', $button, $htmlPage);
    $htmlPage = str_replace('<hiddenCharID />', $hiddenCharID, $htmlPage);

    return $htmlPage;
}

function Char_Form($toEdit) {
        
    $messaggioForm = "";
    $name = ""; $race = ""; $class = ""; $background = ""; $alignment = ""; $traits = ""; $ideals = ""; $bonds = ""; $flaws = "";

    staged_session();

    $html = file_get_contents('..' . DIRECTORY_SEPARATOR . 'html'. DIRECTORY_SEPARATOR . 'character creation.html');

    $html = setup($html);
    $html = preparePage($html, $toEdit);

    if ( isset($_SESSION['CharFormPOST']) ) {   // $_SESSION['CharFormPOST'] == $_POST prima del redirect
        $name = $_SESSION['CharFormPOST']['cname'];    //estraggo dal post della form le informazioni contenute
        $race = $_SESSION['CharFormPOST']['crace'];
        $class = $_SESSION['CharFormPOST']['cclass'];
        $background = $_SESSION['CharFormPOST']['cbackground'];
        $alignment = $_SESSION['CharFormPOST']['calignment'];
        $traits = $_SESSION['CharFormPOST']['ctraits'];
        $ideals = $_SESSION['CharFormPOST']['cideals'];
        $bonds = $_SESSION['CharFormPOST']['cbonds'];
        $flaws = $_SESSION['CharFormPOST']['cflaws'];
        //Fare i controlli sugli input
        //Uso variabili booleane, true se la variabile che controlla passa il check, false altrimenti

        $check_name = preg_match("/^[a-z][a-z ,.'-]{2,20}$/i", $name);// trim dopo, accetta sequenze strane ,,,,---...  //preg_match("/\\S+/",$name);
        //$check_race = ;            //provengono da select, non possono essere sbagliati, no?
        //$check_class = ;
        //$check_background = ;
        //$check_alignment = ;
        $check_traits = checkText($traits);
        $check_ideals = checkText($ideals);
        $check_bonds = checkText($bonds);
        $check_flaws = checkText($flaws);

        if($check_name && $check_traits && $check_ideals && $check_bonds && $check_flaws){
            //se passo i controlli allora passo gli input alla costruzione di dati per il DB.
            $character = new Character(
                0,    // ID qui inutile, non viene considerato per inserimento DB (e poi oggetto character viene distrutto) (aggiungere valore di default?)
                $name,
                $race, $class, $background, $alignment,    // Ok, select
                clean_input($traits), 
                clean_input($ideals), 
                clean_input($bonds), 
                clean_input($flaws),
            );

            if(isset($_SESSION['username'])) {

                $character->set_author($_SESSION['username']);

                $db = new DBinterface();
                $openConnection = $db->openConnection();

                if ($openConnection == true) {
                    $result = $toEdit ? 
                    $db->setCharacter($character, $_SESSION['CharFormPOST']["charID"]) : 
                    $db->addCharacter($character); // TO FIX $_SESSION['user_id'] ?

                    if($result == true) {    // conferma ed errori con str_replace o banner_salvataggio.html ?
                        $_SESSION['banners'] = $toEdit ? 
                        "modifica_documento_confermata" : 
                        "creazione_documento_confermata";

                        $name = ""; $race = ""; $class = ""; $background = ""; $alignment = ""; $traits = ""; $ideals = ""; $bonds = ""; $flaws = "";
                        unset($_SESSION['CharFormPOST']);
                        header("Location: area_personale.php");
                        exit();
                    }
                    else {
                        // Can't insert in DB
                        $messaggioForm = '<div id="errori"><p>Errore nella creazione del personaggio. Riprovare.</p></div>'; // (ERRORE LATO DB)
                    }
                }
                else {
                    // Can't connect to DB
                    $messaggioForm = '<div id="errori"><p>Errore nella creazione del personaggio. Riprovare</p></div>'; // (ERRORE LATO Server)
                }

                $db->closeConnection();
            }
            else {  
                // if(!$toEdit) altrimenti errore?
                array_push($_SESSION['stagedPersonaggi'], $character);
                $_SESSION['banners']= "salvataggio_pendente";
            }
        }
        else{
            //se non passo i controlli allora restituisco messaggi adeguati per informare l'utente degli errori di input.
            $messaggioForm = '<div id="errori" style="text-align: center; color: red; background-color: yellow; padding: 1em; border: 3px solid black;"><ul>'; // TO FIX

            if(!$check_name) {
                $namelen = strlen($name);
                if ($namelen < 3) { 
                    $messaggioForm .= '<li>Nome personaggio deve avere almeno 3 caratteri</li>';
                }
                else if ($namelen > 20) {
                    $messaggioForm .= '<li>Nome personaggio deve avere al massimo 20 caratteri</li>';
                }
                else {
                    $messaggioForm .= '<li>Formato Nome non valido: utilizzare solo lettere, spazi, virgole, punti e hypen</li>';
                }
            }

            $charTraits = array("Carattere", "Ideali", "Legami", "Difetti");
            $checkCharTraits = array($check_traits, $check_ideals, $check_bonds, $check_flaws);
            for ($i = 0; $i < 4; $i++) {
                if(!$checkCharTraits[$i]) {
                    $messaggioForm .= '<li>Descrizione per "' . $charTraits[$i]. '" deve essere almeno 10 caratteri </li>';
                }
            }
            $messaggioForm .= '</ul></div>';
        }
    }
    else if ($toEdit) {   // Effettuato solo la prima volta, poi POST avra' valore
        $db = new DBinterface();
        $openConnection = $db->openConnection();

        if ($openConnection == true) {
            $character = $db->getCharacterOfUser($_GET['charID'], $_SESSION['username']);
            if($character) {
                $name = $character->get_name();
                $race = $character->get_race();
                $class = $character->get_class();
                $background = $character->get_background();
                $alignment = $character->get_alignment();
                $traits = $character->get_traits();
                $ideals = $character->get_ideals();
                $bonds = $character->get_bonds();
                $flaws = $character->get_flaws();
            }
            else {
                // ERROR PAGE ?
            }
        }
        else {
            // ERROR PAGE ?
        }
    }

    $html = str_replace("<messaggioForm />", $messaggioForm, $html);

    $html = str_replace('<valoreNome />', $name, $html);
    // options
    $html = str_replace("value=\"$race\">", "value=\"$race\" selected=\"selected\">", $html);   // se $race="" nessun replace
    $html = str_replace("value=\"$class\">", "value=\"$class\" selected=\"selected\">", $html);
    $html = str_replace("value=\"$background\">", "value=\"$background\" selected=\"selected\">", $html);
    $html = str_replace("value=\"$alignment\">", "value=\"$alignment\" selected=\"selected\">", $html);
    // text-areas
    $html = str_replace('<valoreTraits />', $traits, $html);
    $html = str_replace('<valoreIdeals />', $ideals, $html);
    $html = str_replace('<valoreBonds />', $bonds, $html);
    $html = str_replace('<valoreFlaws />', $flaws, $html);

    $html = addPossibleBanner($html, "character_creation(FormAction).php");

    // unset($_SESSION['CharFormPOST']);   // Chiudo (come fosse POST) forse no, page refresh

    return $html;
}


?>