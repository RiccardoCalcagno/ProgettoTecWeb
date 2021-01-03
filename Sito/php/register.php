<?php

    $html = file_get_contents("..". DIRECTORY_SEPARATOR . "otherHTMLs". DIRECTORY_SEPARATOR . "register.html");

    if(isset($_SESSION["username"]))
    {
        $html = str_replace("<input id=\"Accesso\" type=\"submit\" value=\"Accedi\">", "<input id=\"Accesso\" type=\"submit\" value=\"Esci\">", $html);
        $html = str_replace("<input id=\"Iscrizione\" type=\"submit\" value=\"Iscrizione\">", "<input id=\"Iscrizione\" type=\"submit\" value=\"Area Personale\">", $html);
    }    
    
    if(isset($_SESSION['beforeAccess'])){
        $html = str_replace('<a href="../Home.html" class="annulla">ANNULLA</a>',"<a href='".$_SESSION['beforeAccess']."' class='annulla'>ANNULLA</a>");
    }
    if($_SESSION['banner']=="creazione_utente_confermata"){

        $html = addPossibleBanner($html);

        switch( saveStaged() ){
            case -1: $_SESSION['banner']="elementi_salvati_errore"; break;
            case 1: $_SESSION['banner']="elementi_salvati"; break;
            case 0: break;
        }
    }




    if(!isset($_SESSION))
    {
        session_start();
    }

    if(isset($_SESSION) && $_SESSION["login"] == false)
    {   
        if(isset($_SESSION["err"]))
        {
            $err = $_SESSION["err"];

            if($err["user_already_exist"])
                str_replace("<p id='UserAlreadyExists' class='hidden'>","<p id='UserAlreadyExists'>", $html);

            if($err["user_empty"])   
                str_replace("<p id='UserEmpty' class='hidden'>","<p id='UserEmpty'>", $html);

            if($err["empty_passwd"])
                str_replace("<p id='PasswordEmpty' class='hidden'>","<p id='PasswordEmpty'>", $html);

            if($err["rep_passwd_err"])
                str_replace("<p id=\"RepPasswdErr\" class=\"hidden\">","<p id=\"RepPasswdErr\">", $html);

            if($err["email_err"])
                str_replace("<p id='EmailErr' class='hidden'>","<p id='EmailErr'>", $html);
            
            if($err["email_already_exist"])
                str_replace("<p id='MailAlreadyExists' class='hidden'>","<p id=MailAlreadyExists'>", $html);

            if($err["empty_name"])
                str_replace("<p id='NameEmpty' class='hidden'>","<p id='NameEmpty'>", $html);

            session_destroy();
        }
        else
        {
            header("Location : area_personale");
        }
    }

    

    echo $html;
?>


