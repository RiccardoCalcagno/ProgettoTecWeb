<?php 

    $db = new DBinterface();

    if(isset($_POST["Personaggio"]))
    {
        $_SESSION["character_id"] = $_POST["Personaggio"];
        header("Location : character.php");
    }

?>