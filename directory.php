<?php
    session_start();
    if(isset($_SESSION["userID"])) {
        $logged_in = true;
        $user_level = $_SESSION["userLevel"];
    } else {
        header("Location: login.html");
        exit();
    }
    include_once("api/Database.php");

    $db = new Database("localhost", "SchoolCitizenAssemblies", "mwd3iqjaesdr", "cPanMT3");
    $connection = $db->get_connection();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Directory - SCA</title>
        <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet'>
        <link rel="stylesheet" href="css/directory.css">
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
                    if($user_level == "Admin") {
                        echo('<a>Admin Panel</a>');
                    }
                    echo('<a href="phpScripts/logout.php">Logout</a>');
                }
                ?>
            </nav>
        </header>

        <main>
        <aside>
                <div class="refinement" id="organisation">
                    <h2>
                        Organisation
                        
                        <div id="expand-button" onclick="hide_refinement(this.parentElement.parentElement)">
                            <span id="vertical"></span>
                            <span id="horizontal"></span>
                        </div>
                    </h2>

                    <?php

                    $statement = $connection->prepare("SELECT DISTINCT Organisation FROM Experts WHERE adminVerified=1");
                    $statement->execute();
                    $result = $statement->fetchAll();

                    if($result) {
                        foreach($result as &$row) {
                            $org = $row["Organisation"];
    
                            echo("
                            <label class='custom-checkbox'>
                                $org
                                <input type='checkbox'>
                                <span class='new-checkbox'></span>
                            </label>
                            ");
                        }
                    }
                    ?>
                </div>

                <div class="refinement" id="ages">
                    <h2>
                        Ages
                        <div id="expand-button" onclick="hide_refinement(this.parentElement.parentElement)">
                            <span id="vertical"></span>
                            <span id="horizontal"></span>
                        </div>
                    </h2>

                    <label class="custom-checkbox">
                        KS1
                        <input type="checkbox">
                        <span class="new-checkbox"></span>
                    </label>

                    <label class="custom-checkbox">
                        KS2
                        <input type="checkbox">
                        <span class="new-checkbox"></span>
                    </label>

                    <label class="custom-checkbox">
                        KS3
                        <input type="checkbox">
                        <span class="new-checkbox"></span>
                    </label>

                    <label class="custom-checkbox">
                        KS4
                        <input type="checkbox">
                        <span class="new-checkbox"></span>
                    </label>

                    <label class="custom-checkbox">
                        KS5
                        <input type="checkbox">
                        <span class="new-checkbox"></span>
                    </label>
                </div>

                <div class="refinement" id="interactions">
                    <h2>
                        Interaction Types
                        <div id="expand-button" onclick="hide_refinement(this.parentElement.parentElement)">
                            <span id="vertical"></span>
                            <span id="horizontal"></span>
                        </div>
                    </h2>
                    
                    <label class="custom-checkbox">
                        Teacher Advice & Information
                        <input type="checkbox">
                        <span class="new-checkbox"></span>
                    </label>

                    <label class="custom-checkbox">
                        Student Interaction
                        <input type="checkbox" onclick="show_interactions()">
                        <span class="new-checkbox"></span>
                    </label>

                    <label class="custom-checkbox">
                        Project Work
                        <input type="checkbox">
                        <span class="new-checkbox"></span>
                    </label>
                </div>

                <div class="refinement" id="student-interactions">
                    <h2>
                        Student Interactions
                        <div id="expand-button" onclick="hide_refinement(this.parentElement.parentElement)">
                            <span id="vertical"></span>
                            <span id="horizontal"></span>
                        </div>
                    </h2>
                    
                    <label class="custom-checkbox">
                        Online
                        <input type="checkbox">
                        <span class="new-checkbox"></span>
                    </label>

                    <label class="custom-checkbox">
                        Face-to-Face
                        <input type="checkbox">
                        <span class="new-checkbox"></span>
                    </label>

                    <label class="custom-checkbox">
                        Resources
                        <input type="checkbox">
                        <span class="new-checkbox"></span>
                    </label>
                </div>

                <div class="refinement" id="distance">
                    <h2>
                        Distance
                        <div id="expand-button" onclick="hide_refinement(this.parentElement.parentElement)">
                            <span id="vertical"></span>
                            <span id="horizontal"></span>
                        </div>
                    </h2>
                    
                    <input type="range" name="distanceRange" min="1" max="180" value="30" oninput="this.nextElementSibling.value = this.value">
                    <output>30</output><p>mi</p>
                </div>
            </aside>

            <div id="right">
                <div id="search-container">
                    <input type="text" placeholder="Search Expertise">
                </div>
                
                <div id="results">
                    <div class="result">
                        <img src="assets/profilePicture.png">
                        <h1>Chris McLean</h1>
                        <h2>Employee at University of Manchester</h2>
                        
                        <table>
                            <tr>
                                <td>This is a resource</td>
                                <td>Link</td>
                            </tr>
                        </table>
                        <p>Contact Expert</p>
                    </div>
                </div>
            </div>
        </main>

        <footer>
            <h1>School Citizen Assemblies</h1>

            <p>info@schoolcitizenassemblies.org</p>
        </footer>
    </body>

    <script>

    function hide_refinement(refinement_div) {
        refinement_div.classList.toggle("collapsed");

        const vert_span = refinement_div.firstElementChild.firstElementChild.firstElementChild;
        if(vert_span.style.transform == "rotate(0deg)") {
            vert_span.style.transform = "rotate(90deg)";
        } else {
            vert_span.style.transform = "rotate(0deg)";
        }
    }

    function show_interactions() {
        const student_interactions = document.querySelector("#student-interactions");

        if(student_interactions.style.maxHeight == "200px") {
            student_interactions.style.maxHeight = "0px";
        } else {
            student_interactions.style.maxHeight = "200px";
        }
    }

    function search() {
        // org = ticked OR ticked ...
        // ages = ticked OR ticked ...
        // interaction types = ticked OR ticked ...
        // student interactions = if(student interaction) {ticked OR ticked}
        // adminVerified = 1
    }

    </script>
</html>