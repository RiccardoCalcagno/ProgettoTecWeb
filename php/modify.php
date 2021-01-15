<?php 

    require_once("DBinterface.php");
    require_once("GeneralPurpose.php");
    require_once("banners.php");

    $modify_user = null;
    $err = array();

    $username = $_POST["future_username"];
    $name_surname = $_POST["NomeCognome"];
    $email = $_POST["future_email"];
    $passwd = $_SESSION["passwd"];
    $birthdate = $_POST["birthdate"];


    if( !empty($_FILES["imgProfilo"]["name"]))
    	$img = ".." . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . "immagini_profilo" . DIRECTORY_SEPARATOR . basename($_FILES["imgProfilo"]["name"]);
    else
    	$img = null;

    try {
        $db = new DBinterface();
        
        $db->openConnection();    

        if(preg_match("/^.{1,}$/", trim($username)) )
        {
            $err["user_empty"] = false;

            if($username != $_SESSION["username"])
                if($db->existUser($username))
                    $err["user_already_exist"] = true;
                else
                    $err["user_already_exist"] = false;
        }
        else
        {
            $err["user_empty"] = true;
        }


        if(strlen(trim($email)) > 0 && filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $err["email_err"] = false;

            if($email != $_SESSION["email"])
                if($db->existMail($email))
                    $err["email_already_exist"] = true;
                else
                    $err["email_already_exist"] = false;
        }
        else
        {
            $err["email_err"] = true;
        }

        if(preg_match("/^[a-z][a-z ,.'-]{2,20}$/i", $name_surname))
        {
            $err["empty_name"] = false;
        }
        else
        {
            $err["empty_name"] = true;
        }

        $db->closeConnection();

        if($img)
        {
            $err["img_err"] = !validate_img($_FILES["imgProfilo"], $img);

            if(!$err["img_err"])
            {
	    	$img = check_file_name($img, basename($_FILES["imgProfilo"]["name"]));

            	if(move_uploaded_file($_FILES["imgProfilo"]["tmp_name"], $img))
            	{
            	    $err["img_err"] = false;
	    	    if($_SESSION["img"] != "../img/img_profilo_mancante.png")
            	    	unlink($_SESSION["img"]);
            	}
            	else
            	{
            	    errorPage("Spiacenti! Errore nel caricamento dell'immagine");
            	    exit();
            	}
            }
            else
            {
            	$img = null;
            }
     	}
    	else
    	{
            $err["img_err"] = false;
            $img = $_SESSION["img"];
    	}


        if(in_array(true, $err))
        {
            $_SESSION["tmpUser"]["username"] = $username;
            $_SESSION["tmpUser"]["name_surname"] = $name_surname;
            $_SESSION["tmpUser"]["email"] = $email;
            $_SESSION["tmpUser"]["birthdate"] = $birthdate;
            $_SESSION["tmpUser"]["img"] = $img; 
            $_SESSION["err"] = $err;
            $_SESSION["result"] = false;
            header("Location: modify_user.php");
        }
        else
        {
        $db->openConnection();
            $modify_user = new UserData($username, $name_surname, $email, $passwd, $birthdate, $img);

            if($db->setUser($modify_user, $_SESSION["username"]))
            {
		$_SESSION["username"] = $username;
                $_SESSION["name_surname"] = $name_surname;
                $_SESSION["email"] = $email;
                $_SESSION["passwd"] = $passwd;
                $_SESSION["birthdate"] = $birthdate;
                $_SESSION["img"] = $img;
                $_SESSION["result"] = true;

                $_SESSION['banners']= "modifica_utente_confermata";
        	$db->closeConnection();
                header("Location: modify_user.php");
            }
            else
            {
        	$db->closeConnection();
        	errorPage("Spiacenti! Errore nella modifica dell'utente"); 
                exit();
            }
        }




    } catch(Exception $e) {
        errorPage("Spiacenti! Qualcosa è andato storto :(");
        exit();
    }

?>
