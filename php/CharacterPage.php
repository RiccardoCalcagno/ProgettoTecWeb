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

                $imgAlt="";
                switch($character->get_race()){
                case 'Umano': $imgAlt.="giovane donna di colore ornata di gioielli che porta con se un bastone";break;
                case 'Elfo': $imgAlt.="elfo incappucciato con una faccia truce armato di due sciabole";break;
                case 'Nano': $imgAlt.="donna nano fantasy dallo sguardo intenso che impugna un grosso maglio";break;
                case 'Halfling': $imgAlt.="piccola donna che sta ridendo, suona uno strumento a corde e porta uno zaino da avventuriero";break;
                case 'Gnome': $imgAlt.="Piccolo essere dai lineamenti femminili orecchie a punta capelli lunghi e regge un arco";break;
                case 'Tiefling': $imgAlt.="donna con pelle di colore rossastro capelli lunghi e orecchie a punta, in abito nobile";break;
                case 'Dragonide': $imgAlt.="bipede dall'aspetto draconico, in una veste da sciamano. Maneggia strumeni per catalizzare la magia";break;
                case 'Mezzelfo': $imgAlt.="uomo dalle orecchie leggermente a punta con indosso vesti pregiate e una coroncina";break;
                case 'Mezzorco': $imgAlt.="orchessa con zanne alla bocca e orecchie a punta. Veste in modo umile e maneggia un martello";break;
                }
                $html = str_replace("<imgAlt />", $imgAlt, $html);

            }
            else {  
                errorPage("Ci spiace informarla che non siamo riusciti a verificare i suoi diritti di visualizzazione su questa scheda giocatore");
                exit();
            }
        }
        else {
            header("Location: 404.php");
            exit();
        }
    }
    else {
        errorPage("EDB");exit();
    }

    $html = addPossibleBanner($html, "CharacterPage.php");

    return $html;
}

function changeCharLayout($html) {
    if(isset($_GET['charLayout'])){
        if($_GET['charLayout'] == 'scheda' ) {
            $html = str_replace(
                'class="disabled" disabled="disabled" aria-label="layout impostato: pergamena"', 
                'class="active" aria-label="clicca per impostare il layout a pergamena"', $html);
              $html = str_replace(
                'class="active" aria-label="clicca per impostare il layout a standard D&D"',
                'class="disabled" disabled="disabled" aria-label="layout impostato: standard D&D"', $html);
              $html = str_replace('id="contentPersonaggio" class="pergamena"', 'id="contentPersonaggio" class="scheda"', $html);
        }
    }


    return $html;
}

if ( session_status() == PHP_SESSION_NONE ) {

    session_start();
    errorPage("Ci spiace informarla che non siamo riusciti a verificare i suoi diritti di visualizzazione su questa scheda giocatore");
    exit();
}
else if ( !isset($_SESSION['username']) ) {

    errorPage("Ci spiace informarla che non siamo riusciti a verificare i suoi diritti di visualizzazione su questa scheda giocatore");
    exit();
}
else if ( isset($_GET['Personaggio']) || isset($_GET['charID'])) {

    $charID = isset($_GET['Personaggio']) ?
        $_GET['Personaggio'] :    
        $_GET['charID'];       

    $html = characterPage($charID);

    if((isset($_GET["Hamburger"])) && ($_GET["Hamburger"]=="yes")){
        $html = str_replace("class=\"hideForHamburger\" ", " ", $html);
        $html = str_replace("{RedirectHamburger}", "../php/CharacterPage.php?Hamburger=no&Personaggio=".$charID, $html);
    }else{
        $html = str_replace("{RedirectHamburger}", "../php/CharacterPage.php?Hamburger=yes&Personaggio=".$charID, $html);
    }

    $html = changeCharLayout($html);
    
    echo $html;
}

?>