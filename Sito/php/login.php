<?php
    require_once("GeneralPurpose.php");

    if(isset($_SESSION['login']) && $_SESSION['login'])
    {
        header("Location : area_personale.php");
    }

    $html = file_get_contents("..". DIRECTORY_SEPARATOR . "otherHTMLs". DIRECTORY_SEPARATOR . "login.html");
    $html = setup($html);   //setup_clear() ?

    if(isset($_SESSION['login']) && !$_SESSION['login'])
    {
        str_replace("<p id=\"loginError\" class=\"hidden\">","<p id=\"loginError\">", $html);
        session_destroy();
    }

    echo $html;
?>