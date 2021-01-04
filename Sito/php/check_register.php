<?php 

    require_once("DBinterface.php");
    require_once("banners.php");
    require_once("GeneralPurpose.php");

    $new_user = null;
    $err = array();

    $username = $_POST["username"];
    $name_surname = $_POST["NomeCognome"];
    $email = $_POST["email"];
    $passwd = $_POST["newPasswd"];
    $rep_passwd = $_POST["PasswdAgan"];
    $birthdate = $_POST["birthdate"];
    $img = $_POST["img"];


    try {
        $db = new DBinterface();
        
        $db->openConnection();

        if(!isset($_SESSION))
        {
            session_start();
            if(strlen(trim($username)) > 0)
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

            if(strlen($passwd) == 0)
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

            if(strlen(trim($name_surname)) > 0)
            {
                $err["empty_name"] = false;
            }
            else
            {
                $err["empty_name"] = true;
            }

            $db->closeConnection();

            if(in_array(true, $err))
            {
                unset($_POST["newPasswd"], $_POST["PasswdAgan"]);
                $_SESSION["err"] = $err;
                header("Location : register.php");
            }
            else
            {
                $db->openConnection();
                $new_user = new UserData($username, $name_surname, $email, $passwd, $birthdate, $username, $img_path);
                if($db->addUser($new_user))
                {
                    $_SESSION["username"] = $username;
                    $_SESSION["name_surname"] = $name_surname;
                    $_SESSION["email"] = $email;
                    $_SESSION["passwd"] = $passwd;
                    $_SESSION["birthdate"] = $birthdate;
                    $_SESSION["img"] = $img;
                    $_SESSION["login"] = true;
                
                }
                else
                {
                    session_destroy();
                    header("Location : 404.php");
                    exit();
                }
                
                $db->closeConnection();
                $_SESSION['banner']="creazione_utente_confermata";
                header("Location : register.php");
            }

        }

    } catch(Exception $e) {
        header("Location : Errore.php");
        exit();
    }
?>