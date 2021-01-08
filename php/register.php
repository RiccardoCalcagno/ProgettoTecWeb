<?php
    require_once("banners.php");
    require_once("GeneralPurpose.php");

    $html = file_get_contents("..". DIRECTORY_SEPARATOR . "html". DIRECTORY_SEPARATOR . "register.html");
    $html = setup($html);   // setup_clear () ?  Ha poco senso questa fuznzione, se accedo alla pagina di registrazione io non sono loggato. Non lascio accedere a questa pagina un utente loggato, quindi i pulsati del menù rimangono invariati


    $username = ""; $name_surname = ""; $img = ""; $birthdate = ""; $email = "";

    if(isset(($_SESSION)['err']) && $_SESSION["err"])
    {  

        $username = $_POST["username"];
        $name_surname = $_POST["NomeCognome"];
        $email = $_POST["email"];
        $birthdate = $_POST["birthdate"];
        $img = $_POST["img"];

        $err = $_SESSION["err"];

        if($err["user_already_exist"])
	{
            $html = str_replace("<p id='UserAlreadyExists' class='hidden'>","<p id='UserAlreadyExists'>", $html);
<<<<<<< HEAD
=======
//		echo "utente esistente";
>>>>>>> 586447182e94c406d9167ad89f9254dfec64a397
	}


        if($err["user_empty"])   
        { 
	   $html = str_replace("<p id='UserEmpty' class='hidden'>","<p id='UserEmpty'>", $html);
<<<<<<< HEAD
=======
//		echo "utente vuoto";
>>>>>>> 586447182e94c406d9167ad89f9254dfec64a397
	}

        if($err["empty_passwd"])
        { 
	   $html = str_replace("<p id='PasswordEmpty' class='hidden'>","<p id='PasswordEmpty'>", $html);
<<<<<<< HEAD
=======
//		echo "password vuota";
>>>>>>> 586447182e94c406d9167ad89f9254dfec64a397
	}


        if($err["rep_passwd_err"])
	{
            $html = str_replace("<p id=\"RepPasswdErr\" class=\"hidden\">","<p id=\"RepPasswdErr\">", $html);
<<<<<<< HEAD
=======
//		echo "password ripetuta errata";
>>>>>>> 586447182e94c406d9167ad89f9254dfec64a397
	}


        if($err["email_err"])
	{
            $html = str_replace("<p id='EmailErr' class='hidden'>","<p id='EmailErr'>", $html);
<<<<<<< HEAD
=======
//		echo "email err";
>>>>>>> 586447182e94c406d9167ad89f9254dfec64a397
	}         
   
        if($err["email_already_exist"])
	{
            $html = str_replace("<p id='MailAlreadyExists' class='hidden'>","<p id=MailAlreadyExists'>", $html);
//		echo "email esistente";
	}

        if($err["empty_name"])
	{
            $html = str_replace("<p id='NameEmpty' class='hidden'>","<p id='NameEmpty'>", $html);
//		echo "nome vuoto";
	}


	unset($_SESSION);
        session_destroy();      
    }

    $html = str_replace("src=\" <img_profilo> \"", "src=\"" . $img ."\"", $html) ;
    $html = str_replace("value=\"<username>\"", "value=\"" . $username . "\"", $html);
    $html = str_replace("value=\"<name>\"", "value=\"" . $name_surname . "\"", $html);
    $html = str_replace("value=\"<email>\"", "value=\"" . $email . "\"", $html);
    $html = str_replace("value=\"<birthdate>\"", "value=\"" . $birthdate . "\"", $html);



    if(isset($_SESSION['banners']) && $_SESSION['banners']=="creazione_utente_confermata"){

//	echo "banner";
        unset($_SESSION["err"]);
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


