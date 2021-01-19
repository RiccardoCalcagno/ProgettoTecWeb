<?php
require_once("DBinterface.php");
require_once("GeneralPurpose.php");
require_once("comments.php");
require_once("banners.php");


if ( !isset($_SESSION['RepCommentPOST']) ) {
    errorPage("EDB");exit();
}

$commentText = clean_input($_SESSION['RepCommentPOST']['contenutoCommento']);
$reportID = $_SESSION['RepCommentPOST']['ReportID'];
unset($_SESSION['RepCommentPOST']);

// Controllare se user non e' ne' autore ne taggato, e report non isExplorable ...

if ( !(trim($commentText) == '') ) {

    $comment = new Comments($commentText, $_SESSION['username'], $reportID);

    $db = new DBinterface();
    $conn = $db->openConnection();
    
    if ($conn) { 
        if( !$db->addComments($comment)) {
            errorPage("EDB");exit();
        }

        $db->closeConnection();
    }
    else {
        errorPage("EDB");exit();
    }
}

header("Location: ReportPage.php?ReportID=". $reportID."#anchorComment");
exit();


?>