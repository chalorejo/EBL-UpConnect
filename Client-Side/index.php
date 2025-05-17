<?php
// home.php or index.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EBL UpConnect</title>

    <!--CSS-->
    <link rel="stylesheet" href="../CSS/stylesheet.css">
    <link rel="stylesheet" href="../CSS/navigation_bar.css">
    <link rel="stylesheet" href="../CSS/home_page.css">
    <link rel="stylesheet" href="../CSS/about.css">
    <link rel="stylesheet" href="../CSS/our_services.css">
    <link rel="stylesheet" href="../CSS/footer.css">

    <link rel="icon" type="image/x-icon" href="https://i.ibb.co/2nNpfB4/Untitled-design-24.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/d5b4e20b91.js" crossorigin="anonymous"></script>

    <!--FONTS-->
    <link href="https://fonts.googleapis.com/css2?family=PT+Serif:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- NAVBAR-->   
    <div class="header">
        <a class="up-logo" href="../PHP/admin_login.php">
            <img id="homeLogo" src="https://i.ibb.co/2nNpfB4/Untitled-design-24.png" alt="UP Logo" />
        </a>
        <div class="university-text">
            <p class="up">University of the Philippines</p>
            <p id="down">MINDANAO</p>
        </div>

        <nav class="desktop-nav">
            <ul>
                <li><a href="#About_background">About</a></li>
                <li><a href="../Client-Side/Requirements.php">Application</a></li>
                <li><a href="../Client-Side/forms-option.php">Permit Forms</a></li>
            </ul>
        </nav>
    </div>

    <!-- HOME PAGE-->  
    <div class="Home_background">
        <div class="Obli">
            <div class="shade"></div>
        </div>

        <div class="Bname">
            <div class="Name">
                <p class="ebl">EBL</p>
                <p class="upconnect">
                    <span>Up</span><span class="wconnect">Connect</span>
                </p>
                <p class="values"><i>"Honor, Excellence, and Service"</i></p>
            </div>
        </div>
    </div>

    <!-- ABOUT-->   
    <section id="About_background">
        <div class="shadeOfAbout">
            <h3>About</h3>
            <div class="about">
                <p>
                    <strong>EBL UpConnect</strong> is designed to simplify the lives of current and future dorm 
                    residents. Provides comprehensive information on policies, amenities, procedures, 
                    permits, reservation, and payments in the most convenient way.
                </p>
            </div>
        </div>
    </section>   

    <!-- SERVICES-->   
    <div class="Services_background">
        <h3>Our Services</h3><br>
        
        <div class="services_container">
            <a href="../Client-Side/Requirements.php">
                <div class="box">
                    <img src="https://i.ibb.co/NZjRjQJ/2.png" alt="reqs_pic"> 
                    <p class="box-title">Application</p>
                    <p class="box-desc">
                        Dormitory Accommodation<br>
                        Requirements upon check-in<br>
                        Other requirements<br>
                        Application Form<br>
                    </p>
                </div>
            </a>

            <a href="../CLient-Side/forms-option.php">
                <div class="box">
                    <img src="https://i.ibb.co/rZj0QZr/1.png" alt="slots_pic">
                    <p class="box-title">Permit and Payment Forms</p>
                    <p class="box-desc">
                        Overnight/Weekend Slip Certification<br>
                        Request for Overnight/Weekend Permit<br>
                        Payment Form
                        <br><br><br>
                    </p>
                </div>  
            </a>

            <a href="../Client-Side/more-info.php">
                <div class="box">
                    <img src="https://i.ibb.co/c6nSfv8/Untitled-design-25.png" alt="forms_pic">
                    <p class="box-title">More Information</p>
                    <p class="box-desc">
                        Policies<br>
                        Accommodations<br>
                        Responsibility of Residents<br>
                        Reminders to Dorm Residents<br>
                        Offenses
                    </p>
                </div>
            </a>
        </div> 
    </div>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer-container">
            <!-- Left: University info -->
            <div class="footer-section">
                <img src="https://i.ibb.co/2nNpfB4/Untitled-design-24.png" alt="UP Mindanao Logo" class="footer-logo">
                <p class="footer-title">University of the Philippines<br><span class="footer-subtitle">MINDANAO</span></p>
                <p class="footer-quote">"Honor, <br>Excellence, and <br>Service"</p>
            </div>

            <!-- Center: Contacts -->
            <div class="footer-section">
                <h3 class="footer-heading">Contacts</h3>
                <div class="footer-contact">
                    <p><strong>Ms. Ann Miraflor Batomalaque</strong><br>
                        Dormitory Manager,<br>
                        Student Housing Services<br>
                        Office of Student Affairs<br>
                        University of the Philippines Mindanao<br>
                    Email: <a href="mailto:shs_osa.upmindanao@up.edu.ph">shs_osa.upmindanao@up.edu.ph</a></p>
                </div>
                <div class="footer-contact">
                    <p><strong>Ms. Shela A. Camilotes</strong><br>
                        Residence Life Coordinator,<br>
                        Student Housing Services<br>
                        Office of Student Affairs<br>
                        University of the Philippines Mindanao<br>
                    Email: <a href="mailto:shs_osa.upmindanao@up.edu.ph">shs_osa.upmindanao@up.edu.ph</a></p>
                </div>
            </div>

            <!-- Right: Socials -->
            <div class="footer-section">
                <h3 class="footer-heading">Follow us on our Socials!</h3>
                <p><i class="fa fa-facebook-square"></i> EBL DORM</p>
                <p><i class="fa fa-envelope"></i> eliaslopezdormitory@gmail.com</p>
            </div>
        </div>

        <div class="footer-bottom">
            © 2025 University of the Philippines Mindanao
        </div>
    </footer>
</body>
</html>
