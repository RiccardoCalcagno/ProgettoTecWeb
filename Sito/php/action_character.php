<?php

    if (!isset($_SESSION)) {
        session_start();
    }

    if(isset($_POST["Personaggio"]))
    {
        $_SESSION["character_id"] = $_POST["Personaggio"];
        header("Location: CharacterPage.php");
    }

    if(isset($_POST["espandi"]) && $_POST["espandi"] == "Pers")
    {
        $_SESSION["espandiPers"] = true;
    }

?>