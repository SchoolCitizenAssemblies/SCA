<?php
    ini_set('display_errors', 1);

    include_once("database.php");

    $db = new Database();

    $statement = $db->prepareStatement(
        "SELECT blah FROM ExpertResources WHERE userID=?",
        "i",
        $_GET["userid"]
    );

    $result = $db->sendQuery($statement);
    print_r(json_encode($result));

?>