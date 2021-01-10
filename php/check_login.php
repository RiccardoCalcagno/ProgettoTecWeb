<?php 
    require_once("DBinterface.php");
    require_once("banners.php");
    require_once("GeneralPurpose.php");

    $rep = new ReportData(null, "heyyyy", null, null, null, null, null);
    array_push($_SESSION['stagedReports'], $rep);

    if(isset($_SESSION['stagedReports'])){
        echo " -NotNUllBefClear: ".$_SESSION['stagedReports']==null;
        if ($_SESSION['stagedReports']){
            echo " -titleBefClear: ".$_SESSION['stagedReports'][0]->get_title();
        }
    }
    echo "preparato";
    clearSession(); // ok ?
    echo "superato";
    if(isset($_SESSION['stagedReports'])){
        echo " -NotNUllAftClear: ".$_SESSION['stagedReports']==null;
        if ($_SESSION['stagedReports']){
            echo " -titleAftClear: ".$_SESSION['stagedReports'][0]->get_title();
        }
    }
    exit();

    $db = new DBinterface();

    try{
        $db->openConnection();


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

        echo "Issetsave: ". isset($_SESSION['stagedReports']);
        if(isset($_SESSION['stagedReports'])){
            echo " -NotNUllsave: ".$_SESSION['stagedReports']==null;
            if ($_SESSION['stagedReports']){
                echo " -titlesave: ".$_SESSION['stagedReports'][0]->get_title();
            }
        }
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
        unset($_POST["password"]);
        header("Location: login.php");
    }
//    $user_data->free(); error ?
    
    } catch (Exception $e)  {
        session_destroy();
        header("Location: Errore.php");
        exit();
    }



?>