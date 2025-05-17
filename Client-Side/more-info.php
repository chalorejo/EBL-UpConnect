<?php
// requirements.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--CSS-->
    <link rel="stylesheet" href="../CSS/stylesheet.css">
    <link rel="stylesheet" href="../CSS/navigation_bar.css">
    <link rel="stylesheet" href="../CSS/footer.css">
    <link rel="stylesheet" href="../CSS/requirements.css">

    <link rel="icon" type="image/x-icon" href="https://i.ibb.co/2nNpfB4/Untitled-design-24.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!--FONTS-->
    <link href="https://fonts.googleapis.com/css2?family=PT+Serif:wght@400;700&display=swap" rel="stylesheet">

    <title>More Information</title>
</head>
<body>
    <!-- NAVBAR-->   
    <div class="header">
        <a class="up-logo" href="admin_login.php">
            <img id="homeLogo" src="https://i.ibb.co/2nNpfB4/Untitled-design-24.png" alt="UP Logo" />
        </a>
        <div class="university-text">
            <p class="up">University of the Philippines</p>
            <p id="down">MINDANAO</p>
        </div>

        <nav class="desktop-nav">
            <ul>
                <li><a href="../Client-Side/index.php">About</a></li>
                <li><a href="../Client-Side/Requirements.php">Application</a></li>
                <li><a href="../Client-Side/forms-option.php">Permit Forms</a></li>
            </ul>
        </nav>
    </div>
    <!--NAVBAR-->

    <!--IMAGE-->
    <div class="image-for-application">
        <img src="https://i.ibb.co/JFkCRXnx/3-Administration-Building.png">
    </div>
    <!--IMAGE-->

    <!--DORM ACCOMMODATION/REQ/OTHER -->
    <div class="req-about">
        <div class="info">
            <div class="class-info">
                <h3>Key Rules and Regulations</h3>
                <p class="ReqBold-second">The EBL Dormitory at UP Mindanao has specific rules and regulations for its residents, 
                    covering topics like property damage, appliance registration, transient boarders, rental agreements, and permit issuance. 
                    These rules aim to ensure a safe and conducive living environment for all residents. </p><br><br>
                
                <p class="ReqBold">Property Damage:</p>
                <ul>
                    <li>Residents are responsible for the full cost of 
                        repair if they damage or deface any property within the 
                        dormitory, regardless of intent. </li><br>
                </ul>
                <p class="ReqBold">Appliance Registration:</p>
                <ul>
                    <li>All appliances brought into the dormitory must be registered with
                        the management upon check-in. Unregistered appliances
                         may be confiscated. </li><br>
                </ul>
                <p class="ReqBold">Transient Boarders:</p>
                <ul>
                    <li>EBL Dorm accepts transient occupants who are r
                        elatives or parents of residents, guests of UP Mindanao personnel, 
                        or delegates/representatives for seminars, workshops, etc.</li><br>
                </ul>

                <p class="ReqBold">Rental Agreement:</p>
                <ul>
                    <li>Residents must pay a two-month advance for dorm fees and 
                        appliances upon admission, with the option to pay the entire semester.</li><br>
                </ul>

                <p class="ReqBold">Permit Issuance:</p>
                <ul>
                    <li>Approval of permits (like for leaving the dormitory) is based 
                        on parent consent, as reflected in the signed Instructional 
                        and Information Sheet.</li><br>
                </ul>

                <p class="ReqBold">Check-in Procedures:</p>
                <ul>
                    <li>Check-in at the EBL Dormitory typically involves submitting 
                        a Dormitory Accommodation Form, class schedule, 
                        and a list of appliances.</li><br>
                </ul>

                

                <br><br>
                <div class="lower-Buttons">
                    <button class="back">
                        <a href="index.php"><i class="fa-solid fa-arrow-left"></i></a>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!--DORM ACCOMMODATION/REQ/OTHER -->

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
