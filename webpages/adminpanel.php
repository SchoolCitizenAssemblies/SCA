<?php
    session_start();

    if(!isset($_SESSION["userLevel"])) {
        if($_SESSION["userLevel"] != "Admin") {
            header("Location: login.php");
            exit();
        }
    }

    include_once("../phpScripts/database.php");
?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>School Citizen Assemblies</title>
        <link rel="stylesheet" href="../css/adminpanel.css">
    </head>
    <body>
        <a class="homeButton" href="scahome.html"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/3/30/Home_free_icon.svg/1200px-Home_free_icon.svg.png"></a>

        <section id="unapprovedExperts">
            <div><h1>Unapproved Experts</h1></div>
            <?php
                // get userID, email where adminVerified = 0 from USers and experts
                // create table with button to verify (then remove from list)
                // button to block email
                // each row in db is row in table

                $db = new Database();
                $statement = $db->prepareStatement(
                    "SELECT Users.email, Experts.organisation, Experts.location FROM Users INNER JOIN Experts ON Users.userID = Experts.userID WHERE Experts.adminVerified = 0",
                    "",
                    array()
                );
                $result = $db->sendQuery($statement, array("email", "org", "loc"));

                echo("<table>");
                echo("<tr><td>Email</td><td>Organisation</td><td>Location</td><td>Approve Expert</td><td>Block Email</td>");
                foreach($result as $row) {
                    $email = $row["email"];
                    $org = $row["org"];
                    $loc = $row["loc"];

                    echo("<tr>");
                    echo("<td> $email </td>");
                    echo("<td> $org </td>");
                    echo("<td> $loc </td>");
                    echo('<td> <button><img src="../assets/check.png"></button> </td>');
                    echo('<td> <button><img src="../assets/remove.png"></button> </td>');
                    echo("</tr>");
                }
                echo("</table>");
            ?>
        </section>

        <section id="admins">
            <div>
                <h1>Admin Users</h1>
                <button><img src="../assets/plus.png"></button>
            </div>
            
            <?php
                // get userID, email from users where userLevel = "Admin"
                // button to delete admin users
                // add button to elevate other user to admin

                $db = new Database();
                $statement = $db->prepareStatement(
                    "SELECT email FROM Users WHERE userLevel = 'Admin'",
                    "",
                    array()
                );
                $result = $db->sendQuery($statement, array("email"));

                echo("<table>");
                echo("<tr><td>Email</td><td>Remove Admin</td><tr>");
                foreach($result as $row) {
                    $email = $row['email'];

                    echo("<tr>");
                    echo("<td> $email </td>");
                    echo('<td> <button><img src="../assets/remove.png"></button> </td>');
                    echo("</tr>");
                }
                echo("</table>");
            ?>
        </section>

        <section id="notifications">
            <div><h1>E-Mail Notifications</h1></div>
        </section>
    </body>
</html>