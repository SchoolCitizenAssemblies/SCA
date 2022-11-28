<?php
    session_start();
    if(isset($_SESSION["user_id"])) {
        $logged_in = true;
        $user_level = $_SESSION["user_level"];
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
        <link href="https://fonts.googleapis.com/css?family=Raleway" rel='stylesheet'>
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
                    echo('<a href="expert-profile.php">Profile</a>');
                }
                ?>
            </nav>
        </header>

        <main>
        <aside>
                <div class="refinement" id="organisation">
                    <h2>
                        Organisation
                        
                        <div class="expand-button" onclick="hide_refinement(this.parentElement.parentElement)">
                            <span id="vertical"></span>
                            <span id="horizontal"></span>
                        </div>
                    </h2>

                    <?php

                    $statement = $connection->prepare("SELECT DISTINCT Organisation FROM Expert WHERE admin_verified=1");
                    $statement->execute();
                    $result = $statement->fetchAll();

                    if($result) {
                        foreach($result as &$row) {
                            $org = $row["Organisation"];
    
                            echo("
                            <label class='custom-checkbox'>
                                $org
                                <input type='checkbox' value='$org'>
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
                        <div class="expand-button" onclick="hide_refinement(this.parentElement.parentElement)">
                            <span id="vertical"></span>
                            <span id="horizontal"></span>
                        </div>
                    </h2>

                    <label class="custom-checkbox">
                        KS1
                        <input type="checkbox" value="ks1">
                        <span class="new-checkbox"></span>
                    </label>

                    <label class="custom-checkbox">
                        KS2
                        <input type="checkbox" value="ks2">
                        <span class="new-checkbox"></span>
                    </label>

                    <label class="custom-checkbox">
                        KS3
                        <input type="checkbox" value="ks3">
                        <span class="new-checkbox"></span>
                    </label>

                    <label class="custom-checkbox">
                        KS4
                        <input type="checkbox" value="ks4">
                        <span class="new-checkbox"></span>
                    </label>

                    <label class="custom-checkbox">
                        KS5
                        <input type="checkbox" value="ks5">
                        <span class="new-checkbox"></span>
                    </label>
                </div>

                <div class="refinement" id="interactions">
                    <h2>
                        Interaction Types
                        <div class="expand-button" onclick="hide_refinement(this.parentElement.parentElement)">
                            <span id="vertical"></span>
                            <span id="horizontal"></span>
                        </div>
                    </h2>
                    
                    <label class="custom-checkbox">
                        Teacher Advice & Information
                        <input type="checkbox" value="teacher_advice">
                        <span class="new-checkbox"></span>
                    </label>

                    <label class="custom-checkbox">
                        Student Interaction
                        <input type="checkbox" onclick="show_interactions()" value="student_interactions">
                        <span class="new-checkbox"></span>
                    </label>

                    <label class="custom-checkbox">
                        Project Work
                        <input type="checkbox" value="project_work">
                        <span class="new-checkbox"></span>
                    </label>
                </div>

                <div class="refinement" id="student-interactions">
                    <h2>
                        Student Interactions
                        <div class="expand-button" onclick="hide_refinement(this.parentElement.parentElement)">
                            <span id="vertical"></span>
                            <span id="horizontal"></span>
                        </div>
                    </h2>
                    
                    <label class="custom-checkbox">
                        Online
                        <input type="checkbox" value="student-online">
                        <span class="new-checkbox"></span>
                    </label>

                    <label class="custom-checkbox">
                        Face-to-Face
                        <input type="checkbox" value="student_f2f">
                        <span class="new-checkbox"></span>
                    </label>

                    <label class="custom-checkbox">
                        Resources
                        <input type="checkbox" value="student_resources">
                        <span class="new-checkbox"></span>
                    </label>
                </div>

                <div class="refinement" id="distance">
                    <h2>
                        Distance
                        <div class="expand-button" onclick="hide_refinement(this.parentElement.parentElement)">
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
                    <img src="assets/searchIcon.png" onclick="search()">
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

    <script src="https://cdn.jsdelivr.net/npm/fuzzysort@2.0.4/fuzzysort.min.js"></script>
    <script>

    function hide_refinement(refinement_div) {
        refinement_div.classList.toggle("collapsed");

        const vert_span = refinement_div.firstElementChild.firstElementChild.firstElementChild;
        if(vert_span.style.transform == "rotate(90deg)") {
            vert_span.style.transform = "rotate(0deg)";
        } else {
            vert_span.style.transform = "rotate(90deg)";
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

        let admin_verified = true,
        orgs = [],
        teacher_advice = false,
        project_work = false,
        student_online = false,
        student_f2f = false,
        student_resources = false,
        does_ks1 = false,
        does_ks2 = false,
        does_ks3 = false,
        does_ks4 = false,
        does_ks5 = false,
        expertise_value = document.querySelector("input[type='text']").value;

        for(const org_checkbox of document.querySelectorAll(".refinement#organisation input")) {
            if(org_checkbox.checked) {
                orgs.push(org_checkbox.value);
            }
        }

        for(const interactions_checkbox of document.querySelectorAll(".refinement#interactions input")) {
            if(interactions_checkbox.checked) {
                switch(interactions_checkbox.value) {
                    case "teacher_advice":
                        teacher_advice = true;
                    case "project_work":
                        project_work = true;
                    case "student_interactions":
                        for(const student_interactions_checkbox of document.querySelectorAll(".refinement#student-interactions input")) {
                            if(student_interactions_checkbox.checked) {
                                switch(student_interactions_checkbox.value) {
                                    case "student_online":
                                        student_online = true;
                                    case "student_f2f":
                                        student_f2f = true;
                                    case "student_resources":
                                        student_resources = true;
                                    default:
                                        console.log("Error with value " + student_interactions_checkbox.value);
                                }
                            }
                        }
                    default:
                        console.log("Error with value "+interactions_checkbox.value);
                }
            }
        }

        for(const ages_checkbox of document.querySelectorAll(".refinement#ages input")) {
            if(ages_checkbox.checked) {
                switch(ages_checkbox.value) {
                    case "ks1":
                        does_ks1 = true;
                    case "ks2":
                        does_ks2 = true;
                    case "ks3":
                        does_ks3 = true;
                    case "ks4":
                        does_ks4 = true;
                    case "ks5":
                        does_ks5 = true;
                }
            }
        }

        let filter = {
            "admin_verified": {"operator": "", "value": [1]},
            "organisation": {"operator": "OR", "value": orgs},
            "does_teacher_advice": {"operator": "OR", "value": [+teacher_advice, 1]},
            "does_project_work": {"operator": "OR", "value": [+project_work, 1]},
            "does_student_online": {"operator": "OR", "value": [+student_online, 1]},
            "does_student_f2f": {"operator": "OR", "value": [+student_f2f, 1]},
            "does_student_resource": {"operator": "OR", "value": [+student_resources, 1]},
            "does_ks1": {"operator": "OR", "value": [+does_ks1, 1]},
            "does_ks2": {"operator": "OR", "value": [+does_ks2, 1]},
            "does_ks3": {"operator": "OR", "value": [+does_ks3, 1]},
            "does_ks4": {"operator": "OR", "value": [+does_ks4, 1]},
            "does_ks5": {"operator": "OR", "value": [+does_ks5, 1]}
        };

        // fetch with options
        fetch("/api/experts?filter=" + btoa(JSON.stringify(filter)))
        .then(response => {
            if(response.ok) {
                return response.json();
            }
        })
        .then(json => {
            // TODO: expertise search
            // TODO: update currently shown experts

            // *** CHECK LOCATION ***

            // get expertise of all experts post filter
            let filter = {"user_id": {"operator": "OR", "value": []}}
            for(const expert of json) {
                filter["user_id"]["value"].push(expert["user_id"]);
            }

            fetch("/api/expertise?filter=" + btoa(JSON.stringify(filter)))
            .then(response => {
                if(response.ok) {
                    return response.json();
                }
            })
            .then(expertise_json => {
                console.log(expertise_json);

                unique_expertise = new Set();
                for(const record of expertise_json) {
                    unique_expertise.add(record["expertise"]);
                }

                // fuzzysort.go(expertise_input, all_expertise, {threshold: **})
                let results = fuzzysort.go(expertise_value, Array.from(unique_expertise), {threshold: -10000});
                console.log(results);
                results.forEach(function (element, index) {results[index] = element["t"]});
                console.log(results);
            });

            
            // for all results (threshold value in fuzzysearch func)
        });
    }

    </script>
</html>