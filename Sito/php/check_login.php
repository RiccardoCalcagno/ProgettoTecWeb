<?php 
    require_once("DBinterface.php");

    $db = new DBinterface();

    try{
        $db->openConnection();

    $user_data = $db->getUser(trim($_POST["username"]), $_POST["passwd"]);

    if($user_data)
    {
        session_start();
        $_SESSION["username"] = $user_data->get_username();
        $_SESSION["name_surname"] = $user_data->get_name_surname();
        $_SESSION["email"] = $user_data->get_email();
        $_SESSION["passwd"] = $user_data->get_passwd();
        $_SESSION["birthdate"] = $user_data->get_birthdate();
        $_SESSION["img"] = $user_data->get_img_path();
        $_SESSION["login"] = true;
        $db->closeConnection();
        //$user_data->free();
        header("Location: area_personale.php");  /* redirect */

    }
    else
    {
        $_SESSION["login"] = false;
        header("Location: login.php");
    }
    
    } catch (Exception $e)  {
    // pagina di errore sarà una location: ...
    }



?>