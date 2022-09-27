<?php
    ini_set('display_errors', 1);

    include_once("database.php");

    /*
        [adminVerified] => 1 /
        [teacherAdvice] => 1 /
        [projectWork] => 1 /
        [studentOnline] => 1 /
        [studentF2F] => 1 /
        [studentResources] => 1 /
        [ages] => ks1,ks2,ks3,ks4,ks5
        [orgs] => wwf,nationaltrust,mbs,unicef,oxfam
        [expertise] => blah /
    */

    $statementString = "SELECT userID, location FROM Experts WHERE adminVerified=1";
    $vars = array();
    $varTypes = "";

    foreach ($_GET as $key => $value) {
        if($key != "adminVerified" && $value == 1) {
            $statementString .= " AND ".$key."=1";
        } else if($key == "expertise") {
            $statementString .= " AND expertise SOUNDS LIKE ?";
            array_push($vars, $value);
            $varTypes .= "s";
        }
    }

    $db = new Database();
    
    $statement = $db->prepareStatement(
        $statementString,
        $varTypes,
        $vars
    );

    $result = $db->sendQuery($statement, array("userID", "location"));
    print_r($result);
?>


