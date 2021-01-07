<?php
require_once("DBinterface.php");
require_once("GeneralPurpose.php");
require_once("character.php");
require_once("banners.php");

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

function getErrors($messaggioForm) {
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
    
    return $messaggioForm;
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
            getErrors($messaggioForm);
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

    // Replace PLACEHOLDERS

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

$toEdit = false;

if ( isset($_GET['charAction']) && $_GET['charAction'] == 'MODIFICA' ) {
    $toEdit =  true;
    
}

echo Char_Form($toEdit);  

?>