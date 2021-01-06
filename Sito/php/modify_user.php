<?php
    require_once("GeneralPurpose.php");

    $html = file_get_contents("..". DIRECTORY_SEPARATOR . "otherHTMLs". DIRECTORY_SEPARATOR . "crea_modifica_utente.html");
    $html = setup($html);   // setup_clear() ?

    $username = $_SESSION["username"];
    $name_surname = $_SESSION["name_surname"];
    $email = $_SESSION["email"];
    $birthdate = $_SESSION["birthdate"];
    $img = $_SESSION["img"];

    if(isset($_SESSION["result"]) && $_SESSION["result"] == false)
    {

        $username = $_POST["future_username"];
        $name_surname = $_POST["NomeCognome"];
        $email = $_POST["future_email"];
        $birthdate = $_POST["birthdate"];
        $img = $_POST["imgProfilo"];

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

    /*if(isset($_SESSION["result"]) && $_SESSION["result"] == true)
    {
        str_replace("<p id=\"Successful\" class=\"hidden\">", "<p id=\"Successful\">", $html);
    }*/

    $html = str_replace("src=\" <img_profilo> \"", "src=\"" . $img ."\"", $html) ;
    $html = str_replace("value=\"<username>\"", "value=\"" . $username . "\"", $html);
    $html = str_replace("value=\"<name>\"", "value=\"" . $name_surname . "\"", $html);
    $html = str_replace("value=\"<email>\"", "value=\"" . $email . "\"", $html);
    $html = str_replace("value=\"<birthdate>\"", "value=\"" . $birthdate . "\"", $html);

    $html = addPossibleBanner($html, "modify_user.php");

    echo $html;
?>