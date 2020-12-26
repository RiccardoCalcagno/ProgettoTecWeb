<?php

    $html = file_get_contents("..". DIRECTORY_SEPARATOR . "otherHTMLs". DIRECTORY_SEPARATOR . "register.html");

    if(!isset($_SESSION))
    {
        session_start();
    }

    if($_SESSION["false"] == false)
    {
        $err = $_SESSION["err"];

        if($err["user_already_exist"])
            str_replace("<p id='UserAlreadyExists' class='hidden'>","<p id='UserAlreadyExists'>", $html);

        if($err["user_empty"])   
            str_replace("<p id='UserEmpty' class='hidden'>","<p id='UserEmpty'>", $html);

        if($err["empty_passwd"])
            str_replace("<p id='PasswordEmpty' class='hidden'>","<p id='PasswordEmpty'>", $html);

        if($err["email_err"])
            str_replace("<p id='EmailErr' class='hidden'>","<p id='EmailErr'>", $html);
            
        if($err["email_already_exist"])
            str_replace("<p id='MailAlreadyExists' class='hidden'>","<p id=MailAlreadyExists'>", $html);

        if($err["empty_name"])
            str_replace("<p id='NameEmpty' class='hidden'>","<p id='NameEmpty'>", $html);

        session_destroy();
    }

    

    echo $html;
?>


