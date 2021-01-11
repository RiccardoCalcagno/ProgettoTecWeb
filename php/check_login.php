<?php 
    require_once("report_data.php");
    require_once("DBinterface.php");
    require_once("banners.php");
    require_once("GeneralPurpose.php");


    session_start();
        /*
    if(!isset($_SESSION['stagedReports'])){
        header("Location: Error.php"); 
        exit();
    }

    if(!$_SESSION['stagedReports']){
        header("Location: Error.php"); 
        exit();
    }

    if($_SESSION['stagedReports']==null){
        header("Location: CreazioneReportPage.php"); 
        exit();
    }

    if($_SESSION['stagedReports']==array()){
        header("Location: 404.php"); 
        exit();
    }
    if(empty($_SESSION['stagedReports'])){
        header("Location: EsploraPage.php"); 
        exit();
    }
    foreach ($_SESSION['stagedReports'] as &$report){
        echo "tit:".$report->get_title();
    }


    $_SESSION['stagedReports']=array();
    $rep = new ReportData(null, "heyyyy", null, null, null, null, null);
    array_push($_SESSION['stagedReports'], $rep);
    */
    echo "provo";
    foreach ($_SESSION['stagedReports'] as &$report){
        echo "titbef:".$report->get_title();
   }








    $_POST["username"]="user";
    $_POST["password"]="user";


    clearSession(); // ok ?

    foreach ($_SESSION['stagedReports'] as &$report){
        echo "titaff:".$report->get_title();
   }

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