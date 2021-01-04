<?php
    require_once("GeneralPurpose.php");

    if( session_status() == PHP_SESSION_NONE ) {
        session_start();
    }

if(isset($_GET["Accedi"]))
{

    if($_GET["Accedi"] == "Accedi")
    {
        $_SESSION['beforeAccess']=$_GET["BeforeAccess"];

        header("Location: login.php");
        exit();
    }

    if($_GET["Accedi"] == "Esci")
    {
        session_start();
        
        session_destroy();
        
        header('Location: index.php');
        
        die();
    }

}

if(isset($_GET["Iscrizione"]))
{

    if($_GET["Iscrizione"] == "Iscrizione")
    {
        $_SESSION['beforeAccess']=$_GET["BeforeAccess"];
        
        header("Location: register.php");
        exit();
    }

    if($_GET["Iscrizione"] == "Area Personale")
    {
        header("Location: area_personale.php");
        exit();
    }
}

header("Location: index.php");





?>