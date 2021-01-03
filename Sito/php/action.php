<?php

if(isset($_POST["Accedi"]))
{
    if($_POST["Accedi"] == "Accedi")
    {
        header("Location: login.php");
        exit();
    }

    if($_POST["Accedi"] == "Esci")
    {
        session_start();
        
        session_destroy();
        
        header('Location: index.php');
        
        die();
    }

}

if(isset($_POST["Iscrizione"]))
{
    if($_POST["Iscrizione"] == "Iscrizione")
    {
        header("Location: register.php");
        exit();
    }

    if($_POST["Iscrizione"] == "Area Personale")
    {
        header("Location: area_personale.php");
        exit();
    }
}

header("Location: index.php");





?>