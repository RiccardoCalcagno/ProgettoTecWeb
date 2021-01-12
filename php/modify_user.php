<?php
    require_once("banners.php");
    require_once("GeneralPurpose.php");

    $html = file_get_contents("..". DIRECTORY_SEPARATOR . "html". DIRECTORY_SEPARATOR . "crea_modifica_utente.html");
    if(!$html)
    header("Location: 404.php");
    //$html = setup($html);   // setup_clear() ?
    unset($_SESSION["first_logged"]);
    unset($_SESSION["listaGiocatori"]);


    $username = $_SESSION["username"];
    $name_surname = $_SESSION["name_surname"];
    $email = $_SESSION["email"];
    $birthdate = $_SESSION["birthdate"];
    $img = $_SESSION["img"];

    if(isset($_SESSION["result"]) && $_SESSION["result"] == false)
    {
//    echo "trovati errori";

        $username = $_SESSION["tmpUser"]["username"];
        $name_surname = $_SESSION["tmpUser"]["name_surname"];
        $email = $_SESSION["tmpUser"]["email"];
        $birthdate = $_SESSION["tmpUser"]["birthdate"];
        //$img = $_SESSION["tmpUser"]["img"];

        $err = $_SESSION["err"];

        if($err["img_err"])
        {
            $html = str_replace("<p id=\"ImgErr\" class=\"hidden\">", "<p id=\"ImgErr\" class=\"text-errore\">", $html);
            $html = str_replace("name=\"imgProfilo\"", "name=\"imgProfilo\" class=\"input-errore\"", $html);
        }

        if($err["user_already_exist"])
        {
            $html = str_replace("<p id=\"UserAlreadyExists\" class=\"hidden\">", "<p id=\"UserAlreadyExists\" class=\"text-errore\">", $html);
            $html = str_replace("name=\"future_username\"", "name=\"future_username\" class=\"input-errore\"", $html);
  //      echo "utente esistente";
        }


        if($err["user_empty"])   
        { 
            $html = str_replace("<p id=\"UserEmpty\" class=\"hidden\">","<p id=\"UserEmpty\" class=\"text-errore\">", $html);
            $html = str_replace("name=\"future_username\"", "name=\"future_username\" class=\"input-errore\"", $html);
//        echo "utente vuoto";
        }

        if($err["rep_passwd_err"])
        {
            $html = str_replace("<p id=\"RepPasswdErr\" class=\"hidden\">","<p id=\"RepPasswdErr\" class=\"text-errore\">", $html);
            $html = str_replace("name=\"PasswdAgan\"", "name=\"PasswdAgan\" class=\"input-errore\"", $html);
            $html = str_replace("name=\"newPasswd\"", "name=\"newPasswd\" class=\"input-errore\"", $html);

//        echo "password ripetuta errata";
        }


        if($err["email_err"])
        {
            $html = str_replace("<p id=\"EmailErr\" class=\"hidden\">","<p id=\"EmailErr\" class=\"text-errore\">", $html);
            $html = str_replace("name=\"future_email\"", "name=\"future_email\" class=\"input-errore\"", $html);

//        echo "email err";
        }         
   
        if($err["email_already_exist"])
        {
            $html = str_replace("<p id=\"MailAlreadyExists\" class=\"hidden\">","<p id=\"MailAlreadyExists\" class=\"text-errore\">", $html);
            $html = str_replace("name=\"future_email\"", "name=\"future_email\" class=\"input-errore\"", $html);

//        echo "email esistente";
        }

        if($err["empty_name"])
        {
            $html = str_replace("<p id=\"NameEmpty\" class=\"hidden\">","<p id=\"NameEmpty\" class=\"text-errore\">", $html);
            $html = str_replace("name=\"NomeCognome\"", "name=\"NomeCognome\" class=\"input-errore\"", $html);

//        echo "nome vuoto";
        } 
        
        if($err["old_password_err"])
        {
            $html = str_replace("<p id=\"OldPasswdErr\" class=\"hidden\">", "<p id=\"OldPasswdErr\">", $html);
            $html = str_replace("name=\"password\"", "name=\"password\" class=\"input-errore\"", $html);

        }

        if($err["new_password_empty"])
        {
            $html = str_replace("<p id=\"NewPasswdEmpty\" class=\"hidden\">","<p id=\"NewPasswdEmpty\">", $html);
            $html = str_replace("name=\"NewPasswd\"", "name=\"newPasswd\" class=\"input-errore\"", $html);
        }

    unset($_SESSION["tmpUser"]);
    }
    unset($_SESSION["result"]);
    unset($_SESSION["err"]);
    unset($err);


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
