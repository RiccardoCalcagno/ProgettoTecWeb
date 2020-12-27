<?php 

    require_once("DBinterface.php");

    $html = file_get_contents("..". DIRECTORY_SEPARATOR . "otherHTMLs". DIRECTORY_SEPARATOR . "crea_modifica_utente.html");
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
        $db = new DBinterface();

        if($old_password == $_SESSION["passwd"])
        {
            $err["old_password_err"] = false;

            if(strlen($new_password) > 0)
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
            $_SESSION["err"] = $err;
            $_SESSION["result"] = false;
            header("Location : modify_user.php");
        }
        else
        {
            $db->openConnection();
            $modify_user = new UserData($username, $name_surname, $email, $new_password, $birthdate, $username, $img_path);
            if($db->setUser($modify_user, $username))
            {
                $db->closeConnection();
                $_SESSION["username"] = $username;
                $_SESSION["name_surname"] = $name_surname;
                $_SESSION["email"] = $email;
                $_SESSION["passwd"] = $new_password;
                $_SESSION["birthdate"] = $birthdate;
                $_SESSION["img"] = $img;
                $_SESSION["result"] = true;
                header("Location : area_personale.php");
            }
            else
            {
                // pagina di errore
            }
        }




    } catch(Exception $e) {
        // pag di err
    }

?>