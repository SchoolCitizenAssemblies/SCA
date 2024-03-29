<?php
    session_start();
    if(isset($_SESSION["user_id"])) {
        $logged_in = true;
        $user_level = $_SESSION["user_level"];
    } else {
        $logged_in = false;
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Toolkits/Guides - SCA</title>
        <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet'>
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,300" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="css/template.css">
        <link rel="stylesheet" href="css/toolkits.css">
    </head>

    <body>
        <header>
            <h1 id="title-heading">
                <a href="/">School Citizen Assemblies</a>
            </h1>

            <nav id="menu">
                <svg id="close-nav" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" overflow="visible" stroke="#ddd" stroke-width="6" stroke-linecap="round">
                    <line x2="50" y2="50" />
                    <line x1="50" y2="50" />
                 </svg>

                <ul id="nav-list">
                    <li>
                        <button class="nav-button" id="about">About Us</button>
                    </li>

                    <li>
                        <button class="nav-button collapsable" id="teacher-resources">Teacher Resources</button>

                        <ul class="subnav" id="teacher-resources">
                            <li><a>SCA Toolkit/Guides</a></li>
                            <li><a>Student Resources</a></li>
                            <!--<li><a>Teacher Resources</a></li>-->
                        </ul>
                    </li>

                    <li>
                        <button class="nav-button collapsable" id="mte">Meet The Experts</button>

                        <ul class="subnav" id="mte">
                            <li><a href="meet-the-experts.php">Meet The Experts</a></li>
                            <li><a href="expert-resources.php">Expert Resources</a></li>
                            <li><a href="directory.php">Directory</a></li>
                        </ul>
                    </li>

                    <?php

                    if($logged_in) {
                        echo('
                            <li>
                                <button class="nav-button collapsable" id="my-account">My Account</button>

                                <div class="subnav" id="my-account">
                        ');
                        if($user_level == "Admin") {
                            echo('<a href="admin-panel.php">Admin Panel</a>');
                        } else if($user_level == "Expert") {
                            echo("<a href=\"expert-profile.php\">Profile</a>");
                        }
                        echo('
                                <a href="phpScripts/logout.php">Logout</a>
                            </div>
                        </li>
                        ');
                    } else {
                        echo('
                            <li>
                                <button class="nav-button" id="login">Login</button>
                            </li>
                        ');
                    }
                    ?>
                </ul>
            </nav>

            <svg id="burger" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 0h24v24H0z" fill="none"></path>
                <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"></path>
            </svg>
        </header>

        <main>
            <h2>SCA Guides</h2>

            <a id="practical" href="guides/SCA Practical Guide 2023.pdf">SCA Practical Guide</a>

            <h3>SCA Activity Guides</h3>
            <ul>
                <li> <a href="guides/SCA Intro Activity Guide (Simple).pdf">SCA Introductory Activity Guide</a> </li>
                <li> <a href="guides/SCA Activity Guide (Core).pdf">SCA Core Activity Guide</a> </li>
                <li> <a href="guides/SCA Extended Activity Guide (with extended activities).pdf">SCA Extended Activity Guide</a> </li>
            </ul>

            <h3>SCA Generic Skills</h3>
            <ul>
                <li> <a href="guides/Generic Skills Guide Part 1 - Draft 2023 (1).pdf">SCA Generic Skills Part 1</a> </li>
                <li> <a href="guides/Generic Skills Guide Part 2 - Draft 2023.pdf">SCA Generic Skills Part 2</a> </li>
                <li> <a href="guides/MINDMAPS -Generic Skills Guide - Draft 2023.pdf">SCA Generic Skills Mindmaps</a> </li>
            </ul>

            <h3>Additional SCA Guides</h3>
            <ul>
                <li> <a href="guides/Empathy Interview SCA Guide.pdf">Empathy Interviews</a> </li>
            </ul>
        </main>

        <footer>
            <h2>© School Citizen Assemblies</h2>

            <p>support@schoolcitizenassemblies.org</p>
        </footer>

        <script src="javascript/header.js"></script>
    </body>
</html>