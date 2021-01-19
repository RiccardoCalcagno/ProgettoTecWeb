<?php 
    require_once("report_data.php");
    require_once("character.php");
    require_once("DBinterface.php");
    require_once("banners.php");
    require_once("GeneralPurpose.php");


    clearSession(); // ok ?

    
    $db = new DBinterface();

    try{
        if(!$db->openConnection()){errorPage("EDB");exit();}


    $user_data = $db->getUser(trim($_POST["username"]), $_POST["password"]);

    if($user_data)
    {
        $_SESSION['userID'] = $user_data->get_id();     // TO FIX Forse 
        $_SESSION["username"] = $user_data->get_username();
        $_SESSION["name_surname"] = $user_data->get_name_surname();
        $_SESSION["email"] = $user_data->get_email();
        $_SESSION["passwd"] = $user_data->get_passwd();
        $_SESSION["birthdate"] = $user_data->get_birthdate();
        $_SESSION["img"] = $user_data->get_img_path();
        $_SESSION["login"] = true;
        $db->closeConnection();

        switch( saveStaged() ){
            case -1: $_SESSION['banners']="elementi_salvati_errore"; break;
            case 1: $_SESSION['banners']="elementi_salvati"; break;
            case 0: break;
        }
        

        header("Location: area_personale.php"); 
        exit();
    }
    else
    {
        $_SESSION["login"] = false;
    $_SESSION["tmp"] = $_POST["username"];
        unset($_POST["password"]);
        header("Location: login.php");
    }
//    $user_data->free(); error ?
    
    } catch (Exception $e)  {
        session_destroy();
        if(isset($db)&&($db)){
        $db->closeConnection();
        }
        errorPage("EDB");
        exit();
    }



?>
