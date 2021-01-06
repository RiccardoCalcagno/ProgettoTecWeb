<?php
    require_once("GeneralPurpose.php");
    require_once("DBinterface.php");

    if( session_status() == PHP_SESSION_NONE ) {
        session_start();
    }

    if(isset($_POST["Personaggio"]))
    {
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

    if(isset($_POST['personaggio']) && $_POST['personaggio'] == "MODIFICA") {
        //$_SESSION["character_id"] settato prima
        $_SESSION['modificaChar'] = true;
        header("Location: character_creation(FormAction).php");
    }

    if(isset($_POST["espandi"]) && $_POST["espandi"] == "Pers")
    {
        $_SESSION["espandiPers"] = true;
    }

?>