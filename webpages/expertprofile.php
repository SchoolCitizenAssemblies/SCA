<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>School Citizen Assemblies</title>
        <link rel="stylesheet" href="../css/header.css">
        <link rel="stylesheet" href="../css/expertprofile.css">
    </head>
    <body>
        <header>
            <img class="logo" src="../assets/tempLogo.png" alt="SCA Logo">
            
            <nav class="navbar">
                <a href="scahome.html" id="homeMenu"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/3/30/Home_free_icon.svg/1200px-Home_free_icon.svg.png"></a>
                
                <div class="dropdown">
                    <button class="dropbtn" onclick="location.href='aboutus.html'">About Us</button>
                    <div class="dropdown-content"></div>
                </div>
                
                <div class="dropdown">
                    <button class="dropbtn" onclick="location.href='teacherresources.html'">Teaching Resources</button>
                    <div class="dropdown-content">
                        <a href="scatoolkit.html">SCA Toolkit</a>
                        <a href="teacherguide.html">Teacher Guide</a>
                        <a href="studentresources.html">Student Resources</a>
                    </div>
                </div>
                
                <div class="dropdown">
                    <button class="dropbtn" onclick="location.href='meettheexperts.php'">Meet the Experts</button>
                    <div class="dropdown-content">
                        <a href="expertresources.html">Expert Resources</a>
                        <a href="directoryresults.php">Directory</a>
                    </div>
                </div>
            </nav>
            
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </header>
        
        <content>
            <div class="container">
                <div class="profile">
                    <h1>Namey Name</h1> <br>

                    <div>
                        <label>Expertise</label>
                        <input type="text" name="expertise">
                    </div>

                    <div>
                        <label>Organisation (if applicable)</label>
                        <input type="text" name="company">
                    </div>

                    <div>
                        <label>
                            Ages
                            <input mbsc-input id="multiple-select-input" placeholder="Please select..." data-dropdown="true" data-input-style="outline" data-label-style="stacked" data-tags="true"/>
                        </label>

                        <select multiple id="multiple-select-select">
                            <option value="1">KS1</option>
                            <option value="2">KS2</option>
                            <option value="3">KS3</option>
                            <option value="4">KS4</option>
                            <option value="5">KS5</option>
                        </select>
                    </div>
                    
                    <div>
                        <label>Face-to-Face</label>
                        <input type="checkbox">
                    </div>
                    
                    <div>
                        <label>Online</label>
                        <input type="checkbox">
                    </div>
                    
                    <div>
                        <label>Teacher Advice</label>
                        <input type="checkbox">
                    </div>

                    <div>
                        <label>Location</label>
                        <input type="text" name="location">
                    </div>

                    <br>
                    <button>Save</button>
                </div>
            </div>
        </content>
    </body>
    
    <script src="../javascript/navbar.js"></script>
</html>