<?php

    if(!isset($_SESSION))
    {
        session_start();
    }

    if(isset($_SESSION['login']) && $_SESSION['login'])
    {
        header("Location : area_personale.php");
    }

    $html = file_get_contents("..". DIRECTORY_SEPARATOR . "otherHTMLs". DIRECTORY_SEPARATOR . "login.html");

    if(isset($_SESSION['beforeAccess'])){
        $html = str_replace('<a href="../Home.html" class="annulla">ANNULLA</a>',"<a href='".$_SESSION['beforeAccess']."' class='annulla'>ANNULLA</a>");
    }

    if(isset($_SESSION["username"]))
    {
        $html = str_replace("<input id=\"Accesso\" type=\"submit\" value=\"Accedi\">", "<input id=\"Accesso\" type=\"submit\" value=\"Esci\">", $html);
        $html = str_replace("<input id=\"Iscrizione\" type=\"submit\" value=\"Iscrizione\">", "<input id=\"Iscrizione\" type=\"submit\" value=\"Area Personale\">", $html);
    }

    if(isset($_SESSION['login']) && !$_SESSION['login'])
    {
        str_replace("<p id=\"loginError\" class=\"hidden\">","<p id=\"loginError\">", $html);
        session_destroy();
    }

    echo $html;
?>