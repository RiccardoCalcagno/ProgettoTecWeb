<?php
    require_once("GeneralPurpose.php");

    if( session_status() == PHP_SESSION_NONE ) {
        session_start();
    }

if(isset($_GET["accesso"]))
{

    if($_GET["accesso"] == "Accedi")
    {
        header("Location: login.php");
        exit();
    }

    if($_GET["accesso"] == "Esci")
    {
        session_start();
        
        session_destroy();
        
        header('Location: ../index.php');
        
        die();
    }

    if($_GET["accesso"] == "Iscrizione")
    {
        
        header("Location: register.php");
        exit();
    }

    if($_GET["accesso"] == "Area Personale")
    {
        header("Location: area_personale.php");
        exit();
    }
}

header("Location: ../index.php");





?>