<?php
    require_once("GeneralPurpose.php");

    clearSession();

if(isset($_GET["Accedi"]))
{
    $_SESSION['beforeAccess']=$_GET["BeforeAccess"];

    if($_GET["Accedi"] == "Accedi")
    {
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
    $_SESSION['beforeAccess']=$_GET["BeforeAccess"];

    if($_GET["Iscrizione"] == "Iscrizione")
    {
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