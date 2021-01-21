<?php
    require_once("GeneralPurpose.php");

    if(isset($_SESSION['login']) && $_SESSION['login'])
    {
        header("Location: area_personale.php");
        exit();
    }

    $username = ""; 

    $html = file_get_contents("..". DIRECTORY_SEPARATOR . "html". DIRECTORY_SEPARATOR . "login.html");
    $html = setup_clear($html);   //setup() ?

    if(isset($_SESSION['login']) && !$_SESSION['login'])
    {
    //echo "errore trovato";
        $username = $_SESSION["tmp"];
        $html = str_replace("<p class=\"loginError hidden\">","<p class=\"loginError text-errore\" role=\"alert\">", $html);
    $html = str_replace("name=\"username\"", "name=\"username\" class=\"input-errore\" tabindex=\"10\"", $html);
    $html = str_replace("name=\"password\"", "name=\"password\" class=\"input-errore\" tabindex=\"10\"", $html);
        session_destroy();
    }

    $html = str_replace("<username/>", $username, $html);

    echo $html;
?>
