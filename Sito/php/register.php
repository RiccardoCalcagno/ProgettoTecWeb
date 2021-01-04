<?php
    require_once("GeneralPurpose.php");

    $html = file_get_contents("..". DIRECTORY_SEPARATOR . "otherHTMLs". DIRECTORY_SEPARATOR . "register.html");
    $html = setup($html);   // setup_clear () ?

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


