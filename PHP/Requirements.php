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

    <title>EBL Requirements</title>
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
                <li><a href="../PHP/index.php">About</a></li>
                <li><a href="../PHP/Requirements.php">Application</a></li>
                <li><a href="../PHP/forms-option.php">Permit Forms</a></li>
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
                <h3>Dormitory Accommodation</h3>
                <p class="ReqBold-second">Prioritization will be categorized into the following:</p>
                <p class="ReqBold">1. First Year Students</p>
                <ul>
                    <li>Living outside Davao City</li>
                </ul>
                <p class="ReqBold">2. UP Mindanao students who belong to the category of FDS (Full Discount with stipend) in their SLAS (Student Learning Assistance System) application results.</p>
                <ul>
                    <li>Living outside Davao City</li>
                    <li>With Good Standing Status in the dormitory (not more than 5 minor offenses).</li>
                </ul>

                <p class="ReqBold">3. Dormers with contract in the Garden and the Kitchen (not more than 5 minor offenses).<br><br>
                    4. UP Mindanao students living outside Davao City with good standing status.
                </p>

                <p class="ReqBold-second">Prioritization of places living outside Davao City</p>
                <ol>
                    <li>From Luzon</li>
                    <li>From Visayas</li>
                    <li>From Mindanao</li>
                </ol>

                <h3>Requirements</h3>
                <ul>
                    <li>Fully Accomplished and Signed Dorm Contract</li>
                    <li>Acknowledgment Accountability Form</li>
                    <li>Data Privacy Law Form</li>
                    <li>1-piece 2X2 ID Picture (new dormer)</li>
                    <li>Payment of Dormitory Rental including appliances (2 months)</li>
                    <li>Photocopy of Form 5 (proof that the student is already enrolled) to be submitted on or before 15 days after the start of the classes</li>
                    <li>Have read the Dormitory Rules and Policies in the Student Handbook</li>
                </ul>

                <h3>Other Requirements</h3>
                <ul>
                    <li>Personal Hygiene Kit and Disinfection Materials</li>
                    <li>Allowed Appliances (laptop, cellphone, electric fan, printer, study lamp)</li>
                    <li>Beddings</li>
                    <li>Drinking Water (Optional)</li>
                    <li>First Aid Kit and Thermometer</li>
                    <li>Room Cleaning Materials (broom, dustpan, feather-duster, etc.)</li>
                    <li>Trash Can</li>
                    <li>Pail and dipper</li>
                </ul>

                <br><br>
                <div class="lower-Buttons">
                    <button class="back">
                        <a href="index.php"><i class="fa-solid fa-arrow-left"></i></a>
                    </button>
                    <div class="continue-button">
                        <p class="continue"><strong>Proceed to Application Form</strong></p>
                        <button class="sub">
                            <a href="applicationform.php"><i class="fa-solid fa-arrow-right"></i></a>
                        </button>
                    </div>
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
