<?php
    require_once("GeneralPurpose.php");
    require_once("DBinterface.php");

    if( session_status() == PHP_SESSION_NONE ) {
        session_start();
    }

    if ( isset($_GET['charLayout']) ) {    // SUBMIT AVVIENE SOLO SE JS NON FUNZIONA

//        $_GET['charLayout'] == 'scheda' ?
        redirect_GET("CharacterPage.php", $_GET);
 //       header("Location: CharacterPage.php?Personaggio=".$_GET['charID']);
    }

    if ( isset($_GET["Personaggio"]) ) {   // AreaPersonale (Character card) 'personaggio' != 'Personaggio' !!!!!!!!!!!!!!!

        /* controllo che il personaggio sia di questo utente */
        $db = new DBinterface();
        $db->openConnection();
        if($db->getCharacterOfUser($_GET["Personaggio"], $_SESSION["username"]))
        {
            $db->closeConnection();
            header("Location: CharacterPage.php?Personaggio=".$_GET["Personaggio"]);
            exit();
        }
        else
        {
            $db->closeConnection();
            header("Location: 404.php");
            exit();
        }
    }

    if( isset($_GET['charAction']) ) {
        
        if($_GET['charAction'] == "MODIFICA") {

           redirect_GET("character_creation(FormAction).php", $_GET);
        }

        if($_POST['charAction'] == "ELIMINA") {

            $_SESSION['banners']="confermare_eliminazione_personaggio";
            header("Location: CharacterPage.php");
        }
    }

    if (isset($_POST['salvaPers'])) {

        $_SESSION['CharFormPOST'] = $_POST;
        
        if ( $_POST['salvaPers'] == 'SALVA SCHEDA' ) {
            header("Location: character_creation(FormAction).php"); // TO FIX KEEP IT LIKE THIS ?
        }
        else {  // $_POST['salvaPers'] == 'SALVA MODIFICA'
            print "AAAAAAAAA";
            header("Location: character_creation(FormAction).php?charAction=MODIFICA&charID=".$_POST['charID']);    //// TO FIX ok like this OR <hiddenCharAction /> ????
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