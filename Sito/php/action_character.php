<?php
    require_once("GeneralPurpose.php");

    if( !isset($_SESSION) ) {
        session_start();
    }
    $_SESSION["username"] = 'user';

    if(isset($_POST["Personaggio"]))
    {
        $_SESSION["character_id"] = $_POST["Personaggio"];
        header("Location: CharacterPage.php");
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