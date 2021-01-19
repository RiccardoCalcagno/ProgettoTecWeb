<?php
    require_once("GeneralPurpose.php");
    require_once("DBinterface.php");

    if( session_status() == PHP_SESSION_NONE ) {
        session_start();
    }

    if ( isset($_GET['charLayout']) ) {    // SUBMIT AVVIENE SOLO SE JS NON FUNZIONA

        //$_GET['charLayout'] == 'scheda' ?
        redirect_GET("CharacterPage.php", $_GET);
        //header("Location: CharacterPage.php?Personaggio=".$_GET['charID']);
    }

    if ( isset($_GET["Personaggio"]) ) {   // AreaPersonale (Character card) 'personaggio' != 'Personaggio' !!!!!!!!!!!!!!!

        /* controllo che il personaggio sia di questo utente */
        $db = new DBinterface();
        $connection=$db->openConnection();
        if(!$connection)
            errorPage("EDB");exit();
        if($db->getCharacterOfUser($_GET["Personaggio"], $_SESSION["username"]))
        {
            $db->closeConnection();
            header("Location: CharacterPage.php?Personaggio=".$_GET["Personaggio"]);
            exit();
        }
        else
        {
            $db->closeConnection();     // Potrebbe essere un errore DB ma anche un azione volontaria malevola (più probabilmente)
            errorPage("Ci spiace informarla che non siamo riusciti a verificare i suoi diritti di visualizzazione su questa scheda giocatore");
            exit();
        }
    }

    if( isset($_GET['charAction']) ) {
        
        if($_GET['charAction'] == "MODIFICA") {

           redirect_GET("character_creation(FormAction).php", $_GET);
        }

        if($_GET['charAction'] == "ELIMINA") {

            $_SESSION['banners']="confermare_eliminazione_personaggio";
            $_SESSION['banners_ID'] = $_GET['charID'];  // PASSAGGIO PER CAMPO HIDDEN
            header("Location: CharacterPage.php?charID=".$_GET['charID']."#footAction");  // Ignora ELIMINA on Page Refresh (voluto)
        }
    }

    if (isset($_POST['salvaPers'])) {

        $_SESSION['CharFormPOST'] = $_POST;
        
        if ( $_POST['salvaPers'] == 'SALVA SCHEDA' ) {
            header("Location: character_creation(FormAction).php"); // TO FIX KEEP IT LIKE THIS ?
        }
        else {  // $_POST['salvaPers'] == 'SALVA MODIFICA'
            header("Location: character_creation(FormAction).php?charAction=MODIFICA&charID=".$_POST['charID']);    //// TO FIX ok like this OR <hiddenCharAction /> ????
        }
    }

    if (isset($_POST['documento']) && $_POST['documento'] == 'ELIMINA' ) {
    // Serve una pagina solo per questo ? o anche solo una function ? .
        $db = new DBinterface();
        if($db->openConnection()) {

            $done = $db->deleteCharacter($_POST['charID']);
            $db->closeConnection();
    
            if ($done) {
                // FEEDBACK? like $_SESSION['AP_message'] = 'Cancellazione Riuscita.'; // e poi metterlo in AreaP
                header("Location: area_personale.php");
            }
            else {
                errorPage("EDB");exit();
            }
        }
        else {
            errorPage("EDB");exit();
        }

    }

    if(isset($_GET["espandi"]))
    {
        $_SESSION["espandiPers"] = true;
        header("Location: area_personale.php#personaggiUtente");
        exit();
    }

    if(isset($_GET["riduci"]))
    {
        $_SESSION["espandiPers"] = false;
        header("Location: area_personale.php#personaggiUtente");
        exit();
    }

?>