<?php
    session_start();
    if(isset($_SESSION["userID"])) {
        $logged_in = true;
        $user_level = $_SESSION["userLevel"];
    } else {
        $logged_in = false;
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Meet the Experts - SCA</title>
        <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet'>
        <link rel="stylesheet" href="css/meet-the-experts.css">
    </head>

    <body>
        <header>
            <h1><a href="home.html">School Citizen Assemblies</a></h1>

            <nav>
                <a href="about.php">About Us</a>
                <a>Teacher Resources</a>
                <a href="meet-the-experts.php">Meet The Experts</a>
            </nav>

            <nav class="subnav">
                <a>Expert Resources</a>
                <a href="directory.php">Directory</a>

                <?php
                if($logged_in) {
                    if($user_level == "") {
                        echo('<a>Logout</a>');
                    }
                    
                    if($user_level == "") {
                        echo('<a>Admin Panel</a>');
                    }
                } else {
                    echo('<a href="login.html">Log In</a>');
                }
                ?>
            </nav>
        </header>

        <main>
            
        </main>

        <footer>
            <h1>School Citizen Assemblies</h1>

            <p>info@schoolcitizenassemblies.org</p>
        </footer>
    </body>
</html>