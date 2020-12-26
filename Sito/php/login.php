<?php

    $html = file_get_contents("..". DIRECTORY_SEPARATOR . "otherHTMLs". DIRECTORY_SEPARATOR . "login.html");

    if(!isset($_SESSION))
    {
        session_start();
    }

    if(isset($_SESSION['login']) && !$_SESSION['login'])
    {
        str_replace("<p id='loginError' class='hidden'>","<p id='loginError'>", $html);
        session_destroy();
    }

    echo $html;
?>