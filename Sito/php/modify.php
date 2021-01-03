<?php 

    require_once("DBinterface.php");
    
    $modify_user = null;
    $err = array();

    $username = $_POST["future_username"];
    $name_surname = $_POST["NomeCognome"];
    $email = $_POST["future_email"];
    $passwd = $_SESSION["passwd"];
    $birthdate = $_POST["birthdate"];
    $img = $_POST["imgProfilo"];

    try {
        $db = new DBinterface();
        
        $db->openConnection();

        if(strlen(trim($username)) > 0)
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
            $_SESSION["err"] = $err;
            $_SESSION["result"] = false;
            header("Location : modify_user.php");
        }
        else
        {
            $modify_user = new UserData($username, $name_surname, $email, $passwd, $birthdate, $username, $img_path);
            if($db->setUser($modify_user, $username))
            {
                $_SESSION["username"] = $username;
                $_SESSION["name_surname"] = $name_surname;
                $_SESSION["email"] = $email;
                $_SESSION["passwd"] = $passwd;
                $_SESSION["birthdate"] = $birthdate;
                $_SESSION["img"] = $img;
                $_SESSION["result"] = true;

                $_SESSION['banners']= "modifica_utente_confermata";

                header("Location : area_personale.php");
            }
            else
            {
                header("Location : error.php");
                exit();
            }
        }




    } catch(Exception $e) {
        header("Location : error.php");
        exit();
    }

?>