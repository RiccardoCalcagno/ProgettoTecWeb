<?php 

    require_once("DBinterface.php");

    $html = file_get_contents("../otherHTMLs/register.html");
    $new_user = null;
    $is_there_error = false;

    try {
        $db = new DBinterface();
        
        $db->openConnection();

        if(!isset($_SESSION))
        {
            session_start();
            if(strlen(trim($_POST["username"])) > 0)
            {
                $err["user_empty"] = false;

                if($db->existUser($_POST["username"]))
                    $err["user_already_exist"] = true;
                else
                    $err["user_already_exist"] = false;

            }
            else
            {
                $err["user_empty"] = true;
            }

            if(strlen(trim($_POST["passwd"])) == 0)
            {
                $err["empty_passwd"] = true;
            }
            else
            {
                $err["empty_passwd"] = false;
            }


            if(strlen(trim($_POST["email"])) > 0 && filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
            {
                $err["email_err"] = false;

                if($db->existMail($_POST["email"]))
                    $err["email_already_exist"] = true;
                else
                    $err["email_already_exist"] = false;
    
            }
            else
            {
                $err["email_err"] = true;
            }

            if(strlen(trim($_POST["name_surname"])) > 0)
            {
                $err["empty_name"] = true;
            }
            else
            {
                $err["empty_name"] = false;
            }

            if(in_array(true, $err))
            {
                header("Location : area_personale.php");
            }
            else
            {
                header("Location : register.php");
            }

        }




    } catch(Exception $e) {
        // pag di err
    }
?>