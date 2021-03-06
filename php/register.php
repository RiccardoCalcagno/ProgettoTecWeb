<?php
    require_once("report_data.php");
    require_once("character.php");
    require_once("banners.php");
    require_once("GeneralPurpose.php");

    $html = file_get_contents("..". DIRECTORY_SEPARATOR . "html". DIRECTORY_SEPARATOR . "register.html");
    $html = setup($html); 

    unset($_SESSION["first_logged"]);
    unset($_SESSION["listaGiocatori"]);

    $username = ""; $name_surname = ""; $birthdate = ""; $email = "";

    if(isset(($_SESSION)['err']) && $_SESSION["err"])
    {  

        $username = $_SESSION["tmpUser"]["username"];
        $name_surname = $_SESSION["tmpUser"]["NomeCognome"];
        $email = $_SESSION["tmpUser"]["email"];
        $birthdate = $_SESSION["tmpUser"]["birthdate"];

        $err = $_SESSION["err"];

        if($err["img_err"])
        {

        $html = str_replace("<p id=\"ImgErr\" class=\"hidden\">", "<p id=\"ImgErr\" class=\"text-errore\" role=\"alert\" >", $html);
        $html = str_replace("name=\"imgProfilo\"", "name=\"imgProfilo\" class=\"input-errore\"", $html);
        }

        if($err["user_already_exist"])
        {
            $html = str_replace("<p id=\"UserAlreadyExists\" class=\"hidden\">", "<p id=\"UserAlreadyExists\" class=\"text-errore\" role=\"alert\">", $html);
        $html = str_replace("name=\"username\"", "name=\"username\" class=\"input-errore\"", $html);

        }


        if($err["user_empty"])   
        { 
       $html = str_replace("<p id=\"UserEmpty\" class=\"hidden\">","<p id=\"UserEmpty\" class=\"text-errore\" role=\"alert\" >", $html);
       $html = str_replace("name=\"username\"", "name=\"username\" class=\"input-errore\"", $html);

        }

        if($err["empty_passwd"])
        {
       $html = str_replace("<p id=\"PasswordEmpty\" class=\"hidden\">","<p id=\"PasswordEmpty\" class=\"text-errore\" role=\"alert\">", $html);
       $html = str_replace("name=\"newPasswd\"", "name=\"newPasswd\" class=\"input-errore\"", $html);

        }


        if($err["rep_passwd_err"])
    {
            $html = str_replace("<p class=\"RepPasswdErr hidden\">","<p class=\"RepPasswdErr text-errore\" role=\"alert\">", $html);
            $html = str_replace("name=\"PasswdAgan\"", "name=\"PasswdAgan\" class=\"input-errore\"", $html);
            $html = str_replace("name=\"newPasswd\"", "name=\"newPasswd\" class=\"input-errore\"", $html);

    }


        if($err["email_err"])
    {
            $html = str_replace("<p id=\"EmailErr\" class=\"hidden\">","<p id=\"EmailErr\" class=\"text-errore\" role=\"alert\">", $html);
            $html = str_replace("name=\"email\"", "name=\"email\" class=\"input-errore\"", $html);

    }         
   
        if($err["email_already_exist"])
    {
            $html = str_replace("<p id=\"MailAlreadyExists\" class=\"hidden\">","<p id=\"MailAlreadyExists\" class=\"text-errore\" role=\"alert\" >", $html);
            $html = str_replace("name=\"email\"", "name=\"email\" class=\"input-errore\"", $html);

    }

        if($err["empty_name"])
    {
            $html = str_replace("<p id=\"NameEmpty\" class=\"hidden\">","<p id=\"NameEmpty\" class=\"text-errore\" role=\"alert\">", $html);
            $html = str_replace("name=\"NomeCognome\"", "name=\"NomeCognome\" class=\"input-errore\"", $html);

    }


        unset($_SESSION["err"]);
        session_destroy();      
    }
    
    $html = str_replace("value=\"<username>\"", "value=\"" . $username . "\"", $html);
    $html = str_replace("value=\"<name>\"", "value=\"" . $name_surname . "\"", $html);
    $html = str_replace("value=\"<email>\"", "value=\"" . $email . "\"", $html);
    $html = str_replace("value=\"<birthdate>\"", "value=\"" . $birthdate . "\"", $html);

    if(isset($_SESSION['banners']) && $_SESSION['banners']=="creazione_utente_confermata"){

        unset($_SESSION["err"]);
        unset($_SESSION["tmpUser"]);

        $html = addPossibleBanner($html, "area_personale.php");

        switch( saveStaged() ){
            case -1: $_SESSION['banners']="elementi_salvati_errore"; break;
            case 1: $_SESSION['banners']="elementi_salvati"; break;
            case 0: break;
        }
    }else{
        $html = addPossibleBanner($html, "area_personale.php");
    }

    if((isset($_GET["Hamburger"])) && ($_GET["Hamburger"]=="yes")){
        $html = str_replace("class=\"hideForHamburger\" ", " ", $html);
        $html = str_replace("{RedirectHamburger}", "../php/register.php?Hamburger=no", $html);
    }else{
        $html = str_replace("{RedirectHamburger}", "../php/register.php?Hamburger=yes", $html);
    }

    echo $html;
?>


