<?php

    $html = file_get_contents("../otherHTMLs/crea_modifica_utente.html");

    if(!isset($_SESSION))
    {
        session_start();
    }

    if(in_array(true, $_SESSION["err"]))
    {
        if()
        str_replace("<p id='UserAlreadyExists' class='hidden'>","<p id='UserAlreadyExists'>", $html);
        str_replace("<p id='UserEmpty' class='hidden'>","<p id='UserEmpty'>", $html);
        str_replace("<p id='PasswordEmpty' class='hidden'>","<p id='PasswordEmpty'>", $html);

        str_replace("<p id='loginError' class='hidden'>","<p id='loginError'>", $html);
        session_destroy();
    }

    

    echo $html;
?>


