<?php
    require_once("GeneralPurpose.php");

    if( session_status() == PHP_SESSION_NONE ) {
        session_start();
    }

    if(isset($_POST["Personaggio"])){

        $_SESSION["character_id"] = $_POST["Personaggio"];
        header("Location: CharacterPage.php");
    
        if($_POST['personaggio'] == "MODIFICA") {
            //$_SESSION["character_id"] settato prima
            $_SESSION['modificaChar'] = true;
            header("Location: character_creation(FormAction).php");
        }

        if($_POST['personaggio'] == "ELIMINA") {

            $_SESSION['banners']="confermare_eliminazione_personaggio";
        }
    }

    if(isset($_POST["espandi"]) && $_POST["espandi"] == "Pers")
    {
        $_SESSION["espandiPers"] = true;
    }

?>