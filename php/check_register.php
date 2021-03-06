<?php 

    require_once("DBinterface.php");
    require_once("banners.php");
    require_once("GeneralPurpose.php");

    session_start();

    $new_user = null;
    $err = array();

    $username = $_POST["username"];
    $name_surname = $_POST["NomeCognome"];
    $email = $_POST["email"];
    $passwd = $_POST["newPasswd"];
    $rep_passwd = $_POST["PasswdAgan"];
    $birthdate = $_POST["birthdate"];
   
    if( empty($_FILES["imgProfilo"]["name"]))
    $img = null;
    else
        $img = ".." . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . "immagini_profilo" . DIRECTORY_SEPARATOR . basename($_FILES["imgProfilo"]["name"]);

    try {
        $db = new DBinterface();
        
        if(!$db->openConnection()){errorPage("EDB");exit();}

            if(preg_match("/^.{1,}$/", trim($username)) )
            {
                $err["user_empty"] = false;

                if($db->existUser($username))
                    $err["user_already_exist"] = true;
                else
                    $err["user_already_exist"] = false;

            }
            else
            {
                $err["user_empty"] = true;
            }

            if(!preg_match("/^.{3,}$/", $passwd))
            {
                $err["empty_passwd"] = true;
            }
            else
            {
                $err["empty_passwd"] = false;

                if($passwd == $rep_passwd)
                {
                    $err["rep_passwd_err"] = false;
                }
                else
                {
                    $err["rep_passwd_err"] = true;
                }
                
            }


            if(strlen(trim($email)) > 0 && filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                $err["email_err"] = false;

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
                }
                else
                {
                    errorPage("EDB");
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
        }


            if(in_array(true, $err))
            {
            $_SESSION["tmpUser"]["username"] = $username;
        $_SESSION["tmpUser"]["NomeCognome"] = $name_surname;
        $_SESSION["tmpUser"]["email"] = $email;
        $_SESSION["tmpUser"]["birthdate"] = $birthdate;


                unset($_POST["newPasswd"], $_POST["PasswdAgan"]);
                $_SESSION["err"] = $err;
		if($err["empty_passwd"] || $err["rep_passwd_err"])
                {
		    header("Location: register.php#passField");
		    exit();
		}
		else
		{
		    header("Location: register.php#datiUtenza");
		    exit();
		}
            }
            else
            {
                if(!$db->openConnection()){errorPage("EDB");exit();}
                $new_user = new UserData($username, $name_surname, $email, $passwd, $birthdate, $img);

        if($db->addUser($new_user))
                {
                    $_SESSION["username"] = $username;
                    $_SESSION["name_surname"] = $name_surname;
                    $_SESSION["email"] = $email;
                    $_SESSION["passwd"] = $passwd;
                    $_SESSION["birthdate"] = $birthdate;
                    $_SESSION["img"] = $img;
                    $_SESSION["login"] = true;
                    $_SESSION['banners']="creazione_utente_confermata";
                }
                else
                {
                    session_destroy();
                    $db->closeConnection();
                    errorPage("EDB");
                    exit();
                }
                
                $db->closeConnection();
                
                header("Location: register.php");
                exit();
            }

     


    } catch(Exception $e) {
        if(isset($db)&&($db)){
        $db->closeConnection();
        }
        errorPage("EDB");
        exit();
    }
?>
