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

    // get all GET variables
    $postcode = $_GET["postcode"] ?? "";
    $distance_radius = $_GET["range"] ?? 0;
    $expertise = $_GET["expertise"] ?? "";

    if(isset($_GET["age"])) {
        $selected_ages = explode("|", $_GET["age"], );
    } else {
        $selected_ages = [];
    }

    if(isset($_GET["organisation"])) {
        $selected_orgs = explode("|", $_GET["organisation"], );
    } else {
        $selected_orgs = [];
    }

    if(isset($_GET["interaction"])) {
        $selected_interactions = explode("|", $_GET["interaction"], );
    } else {
        $selected_interactions = [];
    }

    if(isset($_GET["student_interaction"])) {
        $selected_student_interactions = explode("|", $_GET["student_interaction"], );
    } else {
        $selected_student_interactions = [];
    }

    // prepare for results
    define("R", 6371e3); // earth radius (m)

    function location_distance(array $location1, array $location2): float {
        $latitude1_radians = $location1[0] * M_PI / 180;
        $latitude2_radians = $location2[0] * M_PI / 180;
        $latitude_delta = ($location2[0] - $location1[0]) * M_PI / 180;
        $longitude_delta = ($location2[1] - $location1[1]) * M_PI / 180;
        $haversine_a = 
            sin($latitude_delta / 2) *
            sin($latitude_delta / 2) +
            cos($latitude1_radians) *
            cos($latitude2_radians) *
            sin($longitude_delta / 2) *
            sin($longitude_delta / 2);
        $haversine_c = 2 * atan2(
            sqrt($haversine_a),
            sqrt(1 - $haversine_a)
        );
        $distance = R * $haversine_c;
        return $distance;
    }

    function outcode_to_coords($postcode, $type="outcodes") {
        $postcode = str_replace(" ", "", $postcode);
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.postcodes.io/$type/$postcode");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = (array) json_decode(curl_exec($ch), true);
        if($response["status"] == 200) {
            return [
                floatval($response["result"]["latitude"]),
                floatval($response["result"]["longitude"])
            ];
        } else {
            return false;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Directory - SCA</title>
        <link href="https://fonts.googleapis.com/css?family=Raleway" rel='stylesheet'>
        <link rel="stylesheet" href="css/template.css">
        <link rel="stylesheet" href="css/directory.css">
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
                            <li><a>Student Resources</a></li>
                            <li><a>Teacher Resources</a></li>
                            <li><a>SCA Toolkit</a></li>
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
                            echo('<a>Admin Panel</a>');
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

            <div id="search-bar">
                <div id="expertise-search-wrapper">
                    <input type="text" placeholder="Search Expertise">
                    <img src="../assets/searchIcon.png">

                    <ul>
                        <li></li>
                        <li></li>
                        <li></li>
                    </ul>
                </div>
            </div>
        </header>

        <main id="content">
            <aside id="filters">
                <section class="filter-item" id="organisations">
                    <h3 class="filter-item-title">Organisation</h3>

                    <ul class="filter-list">
                        <?php

                        $statement = $connection->prepare("SELECT DISTINCT Organisation FROM Expert WHERE admin_verified=1");
                        $statement->execute();
                        $result = $statement->fetchAll();

                        if($result) {
                            foreach($result as &$row) {
                                $org = $row["Organisation"];
                                $checked = "";
                                if(in_array($org, $selected_orgs)) {
                                    $checked = "checked";
                                }
        
                                echo("
                                <li>
                                    <input class=\"custom-check\" type=\"checkbox\" autocomplete=\"off\" value=\"$org\" $checked>
                                    <label class=\"filter-label\">$org</label>
                                </li>
                                ");
                            }
                        }
                        ?>
                    </ul>
                </section>

                <section class="filter-item" id="ages">
                    <h3 class="filter-item-title">Ages</h3>

                    <ul class="filter-list">
                        <li>
                            <input id="age1" class="custom-check" type="checkbox" autocomplete="off" <?php if(in_array("age1", $selected_ages)) echo("checked"); ?>>
                            <label id="age1" class="filter-label">KS1</label>
                        </li>

                        <li>
                            <input id="age2" class="custom-check" type="checkbox" autocomplete="off" <?php if(in_array("age2", $selected_ages)) echo("checked"); ?>>
                            <label id="age2" class="filter-label">KS2</label>
                        </li>

                        <li>
                            <input id="age3" class="custom-check" type="checkbox" autocomplete="off" <?php if(in_array("age3", $selected_ages)) echo("checked"); ?>>
                            <label id="age3" class="filter-label">KS3</label>
                        </li>

                        <li>
                            <input id="age4" class="custom-check" type="checkbox" autocomplete="off" <?php if(in_array("age4", $selected_ages)) echo("checked"); ?>>
                            <label id="age4" class="filter-label">KS4</label>
                        </li>

                        <li>
                            <input id="age5" class="custom-check" type="checkbox" autocomplete="off" <?php if(in_array("age5", $selected_ages)) echo("checked"); ?>>
                            <label id="age5" class="filter-label">KS5</label>
                        </li>
                    </ul>
                </section>

                <section class="filter-item" id="interactions">
                    <h3 class="filter-item-title">Interaction Types</h3>

                    <ul class="filter-list">
                        <li>
                            <input id="interactions-teacheradvice" class="custom-check" type="checkbox" autocomplete="off" <?php if(in_array("interactions-teacheradvice", $selected_interactions)) echo("checked"); ?>>
                            <label id="interactions-teacheradvice" class="filter-label">Teacher Advice & Information</label>
                        </li>

                        <li>
                            <input id="interactions-student" class="custom-check" type="checkbox" autocomplete="off" <?php if(in_array("interactions-student", $selected_interactions)) echo("checked"); ?>>
                            <label id="interactions-student" class="filter-label">Student Interaction</label>
                        </li>

                        <li>
                            <input id="interactions-project" class="custom-check" type="checkbox" autocomplete="off" <?php if(in_array("interactions-project", $selected_interactions)) echo("checked"); ?>>
                            <label id="interactions-project" class="filter-label">Project Work</label>
                        </li>
                    </ul>
                </section>

                <section class="filter-item <?php if(in_array("interactions-student", $selected_interactions)) echo("shown"); ?>" id="student-interactions">
                    <h3 class="filter-item-title">Student Interactions</h3>

                    <ul class="filter-list">
                        <li>
                            <input id="studentinteractions-f2f" class="custom-check" type="checkbox" autocomplete="off" <?php if(in_array("studentinteractions-f2f", $selected_student_interactions)) echo("checked"); ?>>
                            <label id="studentinteractions-f2f" class="filter-label">Face-to-Face</label>
                        </li>

                        <li>
                            <input id="studentinteractions-online" class="custom-check" type="checkbox" autocomplete="off" <?php if(in_array("studentinteractions-online", $selected_student_interactions)) echo("checked"); ?>>
                            <label id="studentinteractions-online" class="filter-label">Online</label>
                        </li>

                        <li>
                            <input id="studentinteractions-resources" class="custom-check" type="checkbox" autocomplete="off" <?php if(in_array("studentinteractions-resources", $selected_student_interactions)) echo("checked"); ?>>
                            <label id="studentinteractions-resources" class="filter-label">Resources</label>
                        </li>
                    </ul>
                </section>

                <section id="distance">
                    <h3 class="filter-item-title" id="distance">Distance</h3>
                    <input type="checkbox" class="toggle" <?php if($postcode != "") echo("checked") ?>>

                    <p class="distance-label <?php if($postcode == "") echo("disabled") ?>">Within a</p>
                    <select id="radius-choice" <?php if($postcode == "") echo("disabled") ?>>
                        <option value="5" <?php if($distance_radius == "5") echo("selected") ?>>5 mile</option>
                        <option value="10" <?php if($distance_radius == "10") echo("selected") ?>>10 mile</option>
                        <option value="25" <?php if($distance_radius == "25") echo("selected") ?>>25 mile</option>
                        <option value="50" <?php if($distance_radius == "50") echo("selected") ?>>50 mile</option>
                        <option value="100" <?php if($distance_radius == "100") echo("selected") ?>>100 mile</option>
                    </select>
                    <p class="distance-label <?php if($postcode == "") echo("disabled") ?>">radius of</p>

                    <input type="text" id="postcode-entry" placeholder="Postcode" maxlength="8" <?php if($postcode != "") echo("value=\"$postcode\"") ?>>
                    <img class="<?php if($postcode == "") echo("disabled") ?>" id="my-location-button" src="assets/location-icon.png">
                </section>
            </aside>

            <ul id="results">
                <?php
                    // TODO: filter by expertise
                    if($postcode != "") {
                        $current_coords = outcode_to_coords($postcode, "postcodes");
                    }

                    $filters_string = "";

                    if(in_array("age1", $selected_ages)) {
                        $filters_string .= " AND does_ks1 = 1";
                    }

                    if(in_array("age2", $selected_ages)) {
                        $filters_string .= " AND does_ks2 = 1";
                    }

                    if(in_array("age3", $selected_ages)) {
                        $filters_string .= " AND does_ks3 = 1";
                    }

                    if(in_array("age4", $selected_ages)) {
                        $filters_string .= " AND does_ks4 = 1";
                    }

                    if(in_array("age5", $selected_ages)) {
                        $filters_string .= " AND does_ks5 = 1";
                    }

                    if(in_array("interactions-teacheradvice", $selected_interactions)) {
                        $filters_string .= " AND does_teacher_advice = 1";
                    }

                    if(in_array("interactions-project", $selected_interactions)) {
                        $filters_string .= " AND does_project_work = 1";
                    }

                    if(in_array("interactions-student", $selected_interactions)) {
                        if(in_array("studentinteractions-f2f", $selected_student_interactions)) {
                            $filters_string .= " AND does_student_f2f = 1";
                        }

                        if(in_array("studentinteractions-online", $selected_student_interactions)) {
                            $filters_string .= " AND does_student_online = 1";
                        }

                        if(in_array("studentinteractions-resources", $selected_student_interactions)) {
                            $filters_string .= " AND does_student_resource = 1";
                        }
                    }

                    
                    $statement = $connection->prepare("SELECT name, about, location, job_title, organisation,expertise 
                    FROM Expert INNER JOIN Expertise ON Expert.user_id = Expertise.user_id 
                    WHERE admin_verified = 1 $filters_string
                    LIMIT 0, 100;"); // org fits, checkboxes, 
                    $statement->execute();
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

                    
                    while(count($result) > 0) {
                        $current_expertise = $result[0];

                        $name = $current_expertise["name"];
                        $job_title = $current_expertise['job_title'];
                        $org = $current_expertise['organisation'];
                        $about = $current_expertise['about'];

                        echo("
                        <li class=\"profile-result\">
                            <img class=\"profile-picture\" src=\"assets/profilePicture.png\">
                            <h2 class=\"profile-name\">$name</h2>

                            <ul class=\"expertise-list\">
                        ");

                        // group all expertise for each user
                        foreach($result as $i => $tmp_expertise) {
                            if($tmp_expertise["name"] == $current_expertise["name"]) {
                                $result = array_splice($result, $i, $i);
                                $e = ucwords($tmp_expertise["expertise"]);
                                echo("<li class=\"expertise\">$e</li>");
                            }
                        }

                        echo("
                        </ul>
                        <h3 class=\"profile-subheading job\">$job_title at $org</h3>

                        <p class=\"about\">$about</p>
                        ");

                        // filter by postcode
                        if($postcode != "") {
                            $expert_coords = outcode_to_coords($expert["location"]);
                            $distance = location_distance($current_coords, $expert_coords);
                            $distance /= 1609; // convert from meters to miles
                            if($distance <= $_GET["range"]) {
                                echo("<p class=\"distance\">~$distance miles away</p>");
                            }
                        }

                        echo("</li>");
                    }
                ?>
            </ul>
        </main>

        <footer>
            <h2>© School Citizen Assemblies</h2>

            <p>support@schoolcitizenassemblies.org</p>
        </footer>

        <script>
            <?php
                
                $statement = $connection->prepare("SELECT expertise FROM Expertise INNER JOIN Expert ON Expertise.user_id = Expert.user_id WHERE admin_verified=1 GROUP BY expertise ORDER BY COUNT(expertise) DESC");
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                $expertise_js_array_string = "const distinct_expertise = [";

                foreach($result as $expertise) {
                    $expertise_js_array_string .= "\"" . $expertise["expertise"] . "\",";
                }
                $expertise_js_array_string = rtrim($expertise_js_array_string, ",");
                $expertise_js_array_string .= "];\n";
                echo($expertise_js_array_string);
            ?>
        </script>
        <script src="https://cdn.jsdelivr.net/npm/fuzzysort@2.0.4/fuzzysort.min.js"></script>
        <script src="javascript/header.js"></script>
        <script src="javascript/debounce.js"></script>
        <script src="javascript/directory.js"></script>
    </body>
</html>