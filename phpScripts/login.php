<?php
    ini_set("display_errors", 1); // show errors in html (remove after dev)

    include_once("database.php");

    $passHash = hash("sha256", $_POST["password"]); // hash user inputted password

    $db = new Database();

    $statement = $db->prepareStatement(
        "SELECT userID,emailverified,userLevel FROM Users WHERE email = ? AND passwordHash = ?",
        "ss",
        array($_POST["email"], $passHash)
    );    

    $result = $db->sendQuery($statement, array("userID", "verifiedEmail", "userLevel"));

    if(count($result) == 1) {
        if($result[0]["verifiedEmail"] == 1) {
            session_start();
            $_SESSION["userID"] = $result[0]["userID"];
            $_SESSION["userLevel"] = $result[0]["userLevel"];
            header("Location: ../webpages/directoryresults.php"); // redirect to directory
        } else {
            header("Location: ../webpages/login.php?loginError=verifiedEmail"); // report non-verified email
        }
    } else {
        header("Location: ../webpages/login.php?loginError=login"); // report incorrect details
    }
    exit();
?>
