<?php
    require_once("GeneralPurpose.php");
    require_once("DBinterface.php");

    if( session_status() == PHP_SESSION_NONE ) {
        session_start();
    }

    if ( isset($_POST['charLayout']) ) {
        $_SESSION['charLayout'] = $_POST['charLayout'];
        $_POST['charLayout'] == 'scheda' ?
        header("Location: CharacterPageSwitchLayout.php"):
        header("Location: CharacterPage.php");
    }

    if ( isset($_POST["Personaggio"]) ) {   // AreaPersonale (Character card) 'personaggio' != 'Personaggio' !!!!!!!!!!!!!!!
        $_SESSION["character_id"] = $_POST["Personaggio"];

        /* controllo che il personaggio sia di questo utente */
        $db = new DBinterface();
        $db->openConnection();
        if($db->getCharacterOfUser($_SESSION["character_id"], $_SESSION["username"]))
        {
            $db->closeConnection();
            header("Location: CharacterPage.php");
            exit();
        }
        else
        {
            $db->closeConnection();
            header("Location: 404.php");
            exit();
        }
    }

    if( isset($_POST['personaggio']) ) {    // SchedaGiocatore 'personaggio' != 'Personaggio' !!!!!!!!!!!!!!!
        
        if($_POST['personaggio'] == "MODIFICA") {
            //$_SESSION["character_id"] settato prima
            $_SESSION['CharToEdit'] = $_POST['charID'];
            header("Location: character_edit(FormAction).php");
        }

        if($_POST['personaggio'] == "ELIMINA") {

            $_SESSION['banners']="confermare_eliminazione_personaggio";
            header("Location: CharacterPage.php");
        }
    }

    if (isset($_POST['salvaPers'])) {

        $_SESSION['CharFormPOST'] = $_POST;
        
        if ( $_POST['salvaPers'] == 'SALVA SCHEDA' ) {
            header("Location: character_creation(FormAction).php");
        }
        else {  // $_POST['salvaPers'] == 'SALVA MODIFICA'
            header("Location: character_edit(FormAction).php");
        }
    }

    if (isset($_POST['documento']) ) {
        // Delete Character, feedback?
    }

    if(isset($_POST["espandi"]) && $_POST["espandi"] == "Pers")
    {
        $_SESSION["espandiPers"] = true;
    }

?>