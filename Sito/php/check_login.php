<?php 
    require_once("DBinterface.php");
    require_once("banners.php");
    require_once("GeneralPurpose.php");

    clearSession(); // ok ?

    $db = new DBinterface();

    try{
        $db->openConnection();

    $user_data = $db->getUser(trim($_POST["username"]), $_POST["password"]);

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
        
        switch( saveStaged() ){
            case -1: $_SESSION['banner']="elementi_salvati_errore"; break;
            case 1: $_SESSION['banner']="elementi_salvati"; break;
            case 0: break;
        }
        
        if(isset($_SESSION['beforeAccess'])){
            header("Location: ".$_SESSION['beforeAccess']); 
        }else{
            header("Location: area_personale.php"); 
        }
    }
    else
    {
        $_SESSION["login"] = false;
        unset($_POST["password"]);
        header("Location: login.php");
    }
//    $user_data->free(); error ?
    
    } catch (Exception $e)  {
        session_destroy();
        header("Location : error.php");
        exit();
    }



?>