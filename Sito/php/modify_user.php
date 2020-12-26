<?php

    $html = file_get_contents("..". DIRECTORY_SEPARATOR . "otherHTMLs". DIRECTORY_SEPARATOR . "crea_modifica_utente.html");

    if($_SESSION["result"] == false)
    {
        $err = $_SESSION["err"];

        if($err["user_already_exist"])
            str_replace("<p id=\"UserAlreadyExists\" class=\"hidden\">","<p id=\"UserAlreadyExists\">", $html);

        if($err["user_empty"])   
            str_replace("<p id=\"UserEmpty\" class=\"hidden\">","<p id=\"UserEmpty\">", $html);

        if($err["email_err"])
            str_replace("<p id=\"EmailErr\" class=\"hidden\">","<p id=\"EmailErr\">", $html);
            
        if($err["email_already_exist"])
            str_replace("<p id=\"MailAlreadyExists\" class=\"hidden\">","<p id=\"MailAlreadyExists\">", $html);

        if($err["empty_name"])
            str_replace("<p id=\"NameEmpty\" class=\"hidden\">","<p id=\"NameEmpty\">", $html);

        if($err["old_password_err"])
            str_replace("<p id=\"OldPasswdErr\" class=\"hidden\">","<p id=\"OldPasswdErr\">", $html);

        if($err["new_password_empty"])
            str_replace("<p id=\"NewPasswdEmpty\" class=\"hidden\">","<p id=\"NewPasswdEmpty\">", $html);

        if($err["rep_passwd_err"])
            str_replace("<p id=\"RepPasswdErr\" class=\"hidden\">","<p id=\"RepPasswdErr\">", $html);
    
    }

    if($_SESSION["result"] == true)
    {
        str_replace("<p id=\"Successful\" class=\"hidden\">", "<p id=\"Successful\">", $html);
    }

    

    echo $html;
?>