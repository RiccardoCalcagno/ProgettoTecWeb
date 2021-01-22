<?php
require_once("DBinterface.php");
require_once("GeneralPurpose.php");
require_once("character.php");
require_once("banners.php");

function checkText($text) {
    return preg_match("/^[\s\S]{10,}$/", $text); // clean_input dopo
}

function preparePage($htmlPage, $toEdit) {

    $title = ''; $header = ''; $p = ''; $button = ''; $ariaLabelValue = ''; $hiddenCharID = '';

    if ( $toEdit ){

        $title = '<title>Modifica Scheda Giocatore - D&Diary</title>
        <meta name="title" content="Modifica Scheda Giocatore" />
        <meta name="description" content="Modifica la Scheda Giocatore che precedentemente hai creato!" />';

        $header = '<header id="intestazionePagina">
            <h1 id="titolo" tabindex="0">Modifica Scheda Giocatore<span> <a class="puntoInterrogativo" aria-label="Cosa è una Scheda Giocatore?" href="../php/Approf_personaggio.php">?</a></span></h1>
            </header>';

            $p = 'Concludi la modifica salvando la nuova versione della Scheda Giocatore';

            $button = 'SALVA MODIFICA';

            $ariaLabelValue = 'Salva modifica effettuata alla scheda personaggio';

            $hiddenCharID = '<input type="hidden" name="charID" value="'.$_GET['charID'].'" />';     // toEdit => get 
    }
    else  {

        $title = '<title>Creazione Scheda Giocatore - D&Diary</title>
            <meta name="title" content="Creazione Scheda Giocatore" />
            <meta name="description" content="Crea la tua Scheda Giocatore!" />';

        $header = '<header id="intestazionePagina">
            <h1 id="titolo" tabindex="0">Creazione Scheda Giocatore<span> <a class="puntoInterrogativo" aria-label="Cosa è una Scheda Giocatore?" href="../php/Approf_personaggio.php">?</a></span></h1>
            <p> Sei qui per realizzare la tua prima scheda giocatore? Non temere, è facilissimo! Compila questo ridotto insieme di campi per conferire al tuo personaggio le principali caratteristiche.</p>
            <p class="attenzioneP">(<strong class="Attenzione">Attenzione</strong>: per effettuare il salvataggio della scheda sarà necessaria una tua autenticazione)</p>
            </header>';

            $p = 'Concludi la crazione salvando la Scheda Giocatore nella tua Area Personale';

            $button = 'SALVA SCHEDA'; 

            $ariaLabelValue = 'Salva scheda personaggio';

            // $hiddenCharID = '';
    }
    // else {
    //     // ERROR PAGE ? forse non serve neanche 
    // }
    

    $htmlPage = str_replace('<titleValue />', $title, $htmlPage);
    $htmlPage = str_replace('<headerValue />', $header, $htmlPage);
    $htmlPage = str_replace('<pValue />', $p, $htmlPage);
    $htmlPage = str_replace('<buttonValue />', $button, $htmlPage);
    $htmlPage = str_replace('<ariaLabelValue />', $ariaLabelValue, $htmlPage);
    $htmlPage = str_replace('<hiddenCharID />', $hiddenCharID, $htmlPage);

    return $htmlPage;
}

function getErrors($name,$check_name, $check_traits, $check_ideals, $check_bonds, $check_flaws) {
    $messaggioForm = '<div id="errori" class="" tabindex="10" aria-label="sono stati riscontrati alcuni errori. ti trovi all\' inizio della lista di input"><ul>'; 

    if(!$check_name) {
        $namelen = strlen($name);
        if ($namelen < 3) { 
            $messaggioForm .= '<li role=\"alert\">Nome personaggio non valido! Il nome deve avere almeno 3 caratteri</li>';
        }
        else if ($namelen > 20) {
            $messaggioForm .= '<li role=\"alert\">Nome personaggio non valido! Il nome deve avere al massimo 20 caratteri</li>';
        }
        else {
            $messaggioForm .= '<li role=\"alert\">Nome personaggio non valido! Nel nome si possono utilizzare solo lettere, spazi, virgole, punti e <span xml:lang="en">hypen</span></li>';
        }
    }

    $charTraits = array("Carattere", "Ideali", "Legami", "Difetti");
    $checkCharTraits = array($check_traits, $check_ideals, $check_bonds, $check_flaws);
    for ($i = 0; $i < 4; $i++) {
        if(!$checkCharTraits[$i]) {
            $messaggioForm .= '<li role=\"alert\">Il campo "' . $charTraits[$i]. '" non è valido! "' . $charTraits[$i] . '" deve contenere almeno 10 caratteri </li>';
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
                clean_input($flaws)
            );

            if(isset($_SESSION['username'])) {

                $character->set_author($_SESSION['username']);

                $db = new DBinterface();
                $openConnection = $db->openConnection();

                if ($openConnection == true) {
                    $result = $toEdit ? 
                    $db->setCharacter($character, $_SESSION['CharFormPOST']["charID"]) : 
                    $db->addCharacter($character); // TO FIX $_SESSION['user_id'] ?
                    $db->closeConnection();

                    if($result == true) {    // conferma ed errori con str_replace o banner_salvataggio.html ?
                        $_SESSION['banners'] = $toEdit ? 
                        "modifica_documento_confermata" : 
                        "creazione_documento_confermata";

                        $name = ""; $race = ""; $class = ""; $background = ""; $alignment = ""; $traits = ""; $ideals = ""; $bonds = ""; $flaws = "";
                        unset($_SESSION['CharFormPOST']);
                    }
                    else {
                        errorPage("EDB");exit();
                    }
                }
                else {
                    errorPage("EDB");exit();
                }
            }
            else {  
                // if(!$toEdit) altrimenti errore?
                array_push($_SESSION['stagedPersonaggi'], $character);
                $_SESSION['banners']= "salvataggio_pendente";
            }
        }
        else{
            //se non passo i controlli allora restituisco messaggi adeguati per informare l'utente degli errori di input.
            $messaggioForm=getErrors($name, $check_name, $check_traits, $check_ideals, $check_bonds, $check_flaws);
        }
        unset($_SESSION['CharFormPOST']);
    }
    else if ($toEdit) {   // Effettuato solo la prima volta, poi POST avra' valore
        $db = new DBinterface();
        $openConnection = $db->openConnection();

        if ($openConnection == true) {
            $character = $db->getCharacterOfUser($_GET['charID'], $_SESSION['username']);
            $db->closeConnection();
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
                errorPage("Ci spiace informarla che non siamo riusciti a verificare i suoi diritti di visualizzazione su questa scheda giocatore");
                exit();
            }
        }
        else {
            errorPage("EDB");exit();
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


    if((isset($_GET["Hamburger"])) && ($_GET["Hamburger"]=="yes")){
        $html = str_replace("class=\"hideForHamburger\" ", " ", $html);
        $html = str_replace("{RedirectHamburger}", "../php/character_creation(FormAction).php?Hamburger=no", $html);
    }else{
        $html = str_replace("{RedirectHamburger}", "../php/character_creation(FormAction).php?Hamburger=yes", $html);
    }

    $html = addPossibleBanner($html, "character_creation(FormAction).php");

    // unset($_SESSION['CharFormPOST']);   // Chiudo (come fosse POST) forse no, page refresh

    return $html;
}

unset($_SESSION["first_logged"]);
unset($_SESSION["listaGiocatori"]);

$toEdit = false;

if ( isset($_GET['charAction']) && $_GET['charAction'] == 'MODIFICA' ) {
    $toEdit =  true;
    
}

echo Char_Form($toEdit);  

?>