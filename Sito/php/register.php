<?php
    require_once("GeneralPurpose.php");

    require_once("banners.php");
    require_once("GeneralPurpose.php");

    $html = file_get_contents("..". DIRECTORY_SEPARATOR . "otherHTMLs". DIRECTORY_SEPARATOR . "register.html");
    $html = setup($html);   // setup_clear () ?  Ha poco senso questa fuznzione, se accedo alla pagina di registrazione io non sono loggato. Non lascio accedere a questa pagina un utente loggato, quindi i pulsati del menù rimangono invariati
    
    if(isset($_SESSION['beforeAccess'])){
        $html = str_replace('<a href="../Home.html" class="annulla">ANNULLA</a>',"<a href='".$_SESSION['beforeAccess']."' class='annulla'>ANNULLA</a>");
    }

    $username = ""; $name_surname = ""; $img = ""; $birthdate = ""; $email = "";

    if(isset($_SESSION) && $_SESSION["err"])
    {  
        $username = $_POST["username"];
        $name_surname = $_POST["NomeCognome"];
        $email = $_POST["email"];
        $birthdate = $_POST["birthdate"];
        $img = $_POST["img"];

        $err = $_SESSION["err"];

        if($err["user_already_exist"])
            $html = str_replace("<p id='UserAlreadyExists' class='hidden'>","<p id='UserAlreadyExists'>", $html);

        if($err["user_empty"])   
            $html = str_replace("<p id='UserEmpty' class='hidden'>","<p id='UserEmpty'>", $html);

        if($err["empty_passwd"])
            $html = str_replace("<p id='PasswordEmpty' class='hidden'>","<p id='PasswordEmpty'>", $html);

        if($err["rep_passwd_err"])
            $html = str_replace("<p id=\"RepPasswdErr\" class=\"hidden\">","<p id=\"RepPasswdErr\">", $html);

        if($err["email_err"])
            $html = str_replace("<p id='EmailErr' class='hidden'>","<p id='EmailErr'>", $html);
            
        if($err["email_already_exist"])
            $html = str_replace("<p id='MailAlreadyExists' class='hidden'>","<p id=MailAlreadyExists'>", $html);

        if($err["empty_name"])
            $html = str_replace("<p id='NameEmpty' class='hidden'>","<p id='NameEmpty'>", $html);

        session_destroy();      
    }

    $html = str_replace("src=\" <img_profilo> \"", "src=\"" . $img ."\"", $html) ;
    $html = str_replace("value=\"<username>\"", "value=\"" . $username . "\"", $html);
    $html = str_replace("value=\"<name>\"", "value=\"" . $name_surname . "\"", $html);
    $html = str_replace("value=\"<email>\"", "value=\"" . $email . "\"", $html);
    $html = str_replace("value=\"<birthdate>\"", "value=\"" . $birthdate . "\"", $html);

    if($_SESSION['banner']=="creazione_utente_confermata"){

        unset($_SESSION["err"]);
        unset($_POST);

        if(isset($_SESSION['beforeAccess'])){
            $html = addPossibleBanner($html, $_SESSION['beforeAccess']);
        }else{
            $html = addPossibleBanner($html, "area_personale.php");
        } 

        switch( saveStaged() ){
            case -1: $_SESSION['banner']="elementi_salvati_errore"; break;
            case 1: $_SESSION['banner']="elementi_salvati"; break;
            case 0: break;
        }
    }else{
        if(isset($_SESSION['beforeAccess'])){
            $html = addPossibleBanner($html, $_SESSION['beforeAccess']);
        }else{
            $html = addPossibleBanner($html, "area_personale.php");
        }
    }

    echo $html;
?>


