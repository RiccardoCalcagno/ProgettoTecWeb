<?php 

    require_once("DBinterface.php");
    require_once("GeneralPurpose.php");

    $modify_user = null;
    $err = array();

    $username = $_SESSION["username"];
    $name_surname = $_SESSION["name_surname"];
    $email = $_SESSION["email"];
    $old_passwd = $_POST["password"];
    $new_password = $_POST["newPasswd"];
    $repeated_password = $_POST["PasswdAgan"];
    $birthdate = $_SESSION["birthdate"];
    $img = $_SESSION["img"];

    try {

    $_SESSION["old_passwd"] = $old_passwd;

        if($old_passwd == $_SESSION["passwd"])
        {
            $err["old_password_err"] = false;

            if(preg_match("/^.{3,}$/", $new_password))
            {
                $err["new_passwd_empty"] = false;

                if($new_password == $repeated_password)
                {
                    $err["rep_passwd_err"] = false;
                }
                else
                {
                    $err["rep_passwd_err"] = true;
                }
            }
            else
            {
                $err["new_passwd_empty"] = true;
            }
        }
        else
        {
            $err["old_password_err"] = true;
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
            header("Location: modify_user.php#passField");
        }
        else
        {
            $db = new DBinterface();
            if(!$db->openConnection()){errorPage("EDB");exit();}
            $modify_user = new UserData($username, $name_surname, $email, $new_password, $birthdate, $img_path);
            if($db->setPassword($modify_user))
            {
                $db->closeConnection();
                $_SESSION["username"] = $username;
                $_SESSION["name_surname"] = $name_surname;
                $_SESSION["email"] = $email;
                $_SESSION["passwd"] = $new_password;
                $_SESSION["birthdate"] = $birthdate;
                $_SESSION["img"] = $img;
                $_SESSION["result"] = true;

                $_SESSION['banners']= "modifica_utente_confermata";
                
                header("Location: modify_user.php#passField");
            }
            else
            {
                errorPage("EDB");
                exit();
            }
        }




    } catch(Exception $e) {
        errorPage("EDB");
        exit();
    }

?>
