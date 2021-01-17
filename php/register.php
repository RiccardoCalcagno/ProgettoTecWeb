<?php
    require_once("banners.php");
    require_once("GeneralPurpose.php");

    $html = file_get_contents("..". DIRECTORY_SEPARATOR . "html". DIRECTORY_SEPARATOR . "register.html");
    $html = setup($html);   // setup_clear () ?  Ha poco senso questa fuznzione, se accedo alla pagina di registrazione io non sono loggato. Non lascio accedere a questa pagina un utente loggato, quindi i pulsati del menù rimangono invariati

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

        $html = str_replace("<p id=\"ImgErr\" class=\"hidden\">", "<p id=\"ImgErr\" class=\"text-errore\">", $html);
        $html = str_replace("name=\"imgProfilo\"", "name=\"imgProfilo\" class=\"input-errore\"", $html);
        }

        if($err["user_already_exist"])
        {
            $html = str_replace("<p id=\"UserAlreadyExists\" class=\"hidden\">", "<p id=\"UserAlreadyExists\" class=\"text-errore\">", $html);
        $html = str_replace("name=\"username\"", "name=\"username\" class=\"input-errore\"", $html);
  //      echo "utente esistente";
        }


        if($err["user_empty"])   
        { 
       $html = str_replace("<p id=\"UserEmpty\" class=\"hidden\">","<p id=\"UserEmpty\" class=\"text-errore\">", $html);
       $html = str_replace("name=\"username\"", "name=\"username\" class=\"input-errore\"", $html);
//        echo "utente vuoto";
        }

        if($err["empty_passwd"])
        {
       $html = str_replace("<p id=\"PasswordEmpty\" class=\"hidden\">","<p id=\"PasswordEmpty\" class=\"text-errore\">", $html);
       $html = str_replace("name=\"newPasswd\"", "name=\"newPasswd\" class=\"input-errore\"", $html);


//        echo "password vuota";
        }


        if($err["rep_passwd_err"])
    {
            $html = str_replace("<p class=\"RepPasswdErr hidden\">","<p class=\"RepPasswdErr text-errore\">", $html);
            $html = str_replace("name=\"PasswdAgan\"", "name=\"PasswdAgan\" class=\"input-errore\"", $html);
            $html = str_replace("name=\"newPasswd\"", "name=\"newPasswd\" class=\"input-errore\"", $html);

//        echo "password ripetuta errata";
    }


        if($err["email_err"])
    {
            $html = str_replace("<p id=\"EmailErr\" class=\"hidden\">","<p id=\"EmailErr\" class=\"text-errore\">", $html);
            $html = str_replace("name=\"email\"", "name=\"email\" class=\"input-errore\"", $html);

//        echo "email err";
    }         
   
        if($err["email_already_exist"])
    {
            $html = str_replace("<p id=\"MailAlreadyExists\" class=\"hidden\">","<p id=\"MailAlreadyExists\" class=\"text-errore\">", $html);
            $html = str_replace("name=\"email\"", "name=\"email\" class=\"input-errore\"", $html);

//        echo "email esistente";
    }

        if($err["empty_name"])
    {
            $html = str_replace("<p id=\"NameEmpty\" class=\"hidden\">","<p id=\"NameEmpty\" class=\"text-errore\">", $html);
            $html = str_replace("name=\"NomeCognome\"", "name=\"NomeCognome\" class=\"input-errore\"", $html);

//        echo "nome vuoto";
    }


        unset($_SESSION["err"]);
        session_destroy();      
    }
    
    $html = str_replace("value=\"<username>\"", "value=\"" . $username . "\"", $html);
    $html = str_replace("value=\"<name>\"", "value=\"" . $name_surname . "\"", $html);
    $html = str_replace("value=\"<email>\"", "value=\"" . $email . "\"", $html);
    $html = str_replace("value=\"<birthdate>\"", "value=\"" . $birthdate . "\"", $html);



    if(isset($_SESSION['banners']) && $_SESSION['banners']=="creazione_utente_confermata"){

//    echo "banner";
        unset($_SESSION["err"]);
    unset($_SESSION["tmpUser"]);
        unset($_POST);

        $html = addPossibleBanner($html, "area_personale.php");

        switch( saveStaged() ){
            case -1: $_SESSION['banners']="elementi_salvati_errore"; break;
            case 1: $_SESSION['banners']="elementi_salvati"; break;
            case 0: break;
        }
    }else{
        $html = addPossibleBanner($html, "area_personale.php");
    }

    echo $html;
?>


