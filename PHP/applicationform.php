<?php
    include 'connectDB.php'; // connection to database
    $showMessageOnly = $_GET['success'] ?? null;
    $errorMessage = "";
    $isReapplication = false;

    if(isset($_POST['submit'])) {
        $lastName = mysqli_real_escape_string($conn, $_POST['lname']);
        $firstName = mysqli_real_escape_string($conn, $_POST['fname']);
        $middleName = mysqli_real_escape_string($conn, $_POST['mname']);
        $nameExtension = mysqli_real_escape_string($conn, $_POST['ename']);
        $birthday = mysqli_real_escape_string($conn, $_POST['birthdate']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        $studentNum = mysqli_real_escape_string($conn, $_POST['studentNum']);
        $colDept = mysqli_real_escape_string($conn, $_POST['department']);
        $degProg = mysqli_real_escape_string($conn, $_POST['degProg']);
        $yearLevel = mysqli_real_escape_string($conn, $_POST['year']);
        $lotNumber = mysqli_real_escape_string($conn, $_POST['lotNum']);
        $street = mysqli_real_escape_string($conn, $_POST['street']);
        $zipcode = mysqli_real_escape_string($conn, $_POST['zipcode']);
        $country = mysqli_real_escape_string($conn, $_POST['country']);
        $region = mysqli_real_escape_string($conn, $_POST['region']);
        $city = mysqli_real_escape_string($conn, $_POST['city']);
        $barangay = mysqli_real_escape_string($conn, $_POST['barangay']);
        $cellNum = mysqli_real_escape_string($conn, $_POST['cellphoneNum']);
        $telNum = mysqli_real_escape_string($conn, $_POST['telphoneNum']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $secondEmail = mysqli_real_escape_string($conn, $_POST['email2']);
        $contactFname = mysqli_real_escape_string($conn, $_POST['contactPersonF']);
        $contactLname = mysqli_real_escape_string($conn, $_POST['contactPersonL']);
        $contactNum = mysqli_real_escape_string($conn, $_POST['contactNum']);
    
        $status = "pending"; // default status for applicant
        $entryYear = date("Y");

        $uploadDir = 'requirement_file/';
        $uploadReqFile = $uploadDir . basename($_FILES['requirements']['name']);
        $imageFileType = strtolower(pathinfo($uploadReqFile, PATHINFO_EXTENSION));
        $allowedTypes = ['pdf', 'doc', 'docx'];

        if (in_array($imageFileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['requirements']['tmp_name'], $uploadReqFile)) {
            } else {
                $errorMessage = "Error uploading the file.";
                header("Location: ".$_SERVER['PHP_SELF']."?success=2&msg=" . urlencode($errorMessage));
                exit;
            }
        } else {
            $errorMessage = "Invalid file type. Only DOC. DOCX, and PDF are allowed.";
            header("Location: ".$_SERVER['PHP_SELF']."?success=2&msg=" . urlencode($errorMessage));
            exit;
        }

        $checkStudentQuery = "SELECT status FROM dormer WHERE studentNumber = ?";
        $stmtCheckStudent = mysqli_prepare($conn, $checkStudentQuery);
        mysqli_stmt_bind_param($stmtCheckStudent, "s", $studentNum);
        mysqli_stmt_execute($stmtCheckStudent);
        mysqli_stmt_bind_result($stmtCheckStudent, $existingStatus);
    
        if (mysqli_stmt_fetch($stmtCheckStudent)) {
            mysqli_stmt_close($stmtCheckStudent);

            if ($existingStatus === 'Pending') {
                $errorMessage = "You have already submitted your application (status: Pending).";
                header("Location: ".$_SERVER['PHP_SELF']."?success=2&msg=" . urlencode($errorMessage));
                exit;
            } elseif ($existingStatus === 'Active') {
                $errorMessage = "You are already an active dormer.";
                header("Location: ".$_SERVER['PHP_SELF']."?success=2&msg=" . urlencode($errorMessage));
                exit;
            } elseif ($existingStatus === 'Evicted') {
                $errorMessage = "You are no longer eligible to apply (status: Evicted).";
                header("Location: ".$_SERVER['PHP_SELF']."?success=2&msg=" . urlencode($errorMessage));
                exit;
            } else {
                $isReapplication = true;
            }
        } else {
            mysqli_stmt_close($stmtCheckStudent); 
            $isReapplication = false; // this is a new applicant
        }

        $checkAddressQuery = "SELECT addressID FROM address WHERE country = ? AND region = ? AND city = ? AND barangay = ? AND 
                                subdivision_Street = ? AND lotNumber_Prk = ? AND zipCode = ?";

        if ($stmtCheck = mysqli_prepare($conn, $checkAddressQuery)) {
            mysqli_stmt_bind_param($stmtCheck, "ssssssi", $country, $region, $city, $barangay, $street, $lotNumber, $zipcode);
            mysqli_stmt_execute($stmtCheck);
            mysqli_stmt_bind_result($stmtCheck, $existingAddressID);
            
            if (mysqli_stmt_fetch($stmtCheck)) {
                // Address exists
                $addressID = $existingAddressID;
                mysqli_stmt_close($stmtCheck);
            } else {
                mysqli_stmt_close($stmtCheck);

                // Insert new address if not found
                $newAddressQuery = "INSERT INTO address(country, region, city, barangay, subdivision_Street, lotNumber_Prk, zipCode)
                                        VALUES (?, ?, ?, ?, ?, ?, ?)";
                if ($stmtInsertAdd = mysqli_prepare($conn, $newAddressQuery)) {
                    mysqli_stmt_bind_param($stmtInsertAdd, "ssssssi", $country, $region, $city, $barangay, $street, $lotNumber, $zipcode);
                    mysqli_stmt_execute($stmtInsertAdd);

                    $addressID = mysqli_insert_id($conn);
                    mysqli_stmt_close($stmtInsertAdd);
                } else {
                    $errorMessage = "Address insert error: " . mysqli_error($conn);
                    exit;
                }
            }
        } else {
            $errorMessage = "Address check error: " . mysqli_error($conn);
            exit;
        }

        $checkContactQuery = "SELECT contactID FROM contact WHERE contactLastName = ? AND contactFirstName = ? AND contactNumber = ?";

        if ($stmtCheckContact = mysqli_prepare($conn, $checkContactQuery)) {
            mysqli_stmt_bind_param($stmtCheckContact, "sss", $contactLname, $contactFname, $contactNum);
            mysqli_stmt_execute($stmtCheckContact);
            mysqli_stmt_bind_result($stmtCheckContact, $existingContactID);
            
            if (mysqli_stmt_fetch($stmtCheckContact)) {
                // contact ID exists
                $contactID = $existingContactID;
                mysqli_stmt_close($stmtCheckContact);
            } else {
                mysqli_stmt_close($stmtCheckContact);

                // Insert new contact if not found
                $newContactQuery = "INSERT INTO contact(contactLastName, contactFirstName, contactNumber)
                                    VALUES (?, ?, ?)";
                if ($stmtInsertCont = mysqli_prepare($conn, $newContactQuery)) {
                    mysqli_stmt_bind_param($stmtInsertCont, "sss", $contactLname, $contactFname, $contactNum);
                    mysqli_stmt_execute($stmtInsertCont);

                    $contactID = mysqli_insert_id($conn);
                    mysqli_stmt_close($stmtInsertCont);
                } else {
                    $errorMessage = "Contact insert error: " . mysqli_error($conn);
                    exit;
                }
            }
        } else {
            $errorMessage = "Contact check error: " . mysqli_error($conn);
            exit;
        }

        if ($isReapplication) {
            // Fetch old status and requirements before update
            $fetchOldQuery = "SELECT status, requirements FROM dormer WHERE studentNumber = ?";
            $stmtFetchOld = mysqli_prepare($conn, $fetchOldQuery);
            mysqli_stmt_bind_param($stmtFetchOld, "s", $studentNum);
            mysqli_stmt_execute($stmtFetchOld);
            mysqli_stmt_bind_result($stmtFetchOld, $oldStatus, $oldRequirements);

            if (mysqli_stmt_fetch($stmtFetchOld)) {
                mysqli_stmt_close($stmtFetchOld);

                // Insert into history table
                $insertHistoryQuery = "INSERT INTO dormer_history (studentNumber, status, requirements) VALUES (?, ?, ?)";
                $stmtHistory = mysqli_prepare($conn, $insertHistoryQuery);
                mysqli_stmt_bind_param($stmtHistory, "sss", $studentNum, $oldStatus, $oldRequirements);
                mysqli_stmt_execute($stmtHistory);
                mysqli_stmt_close($stmtHistory);
            } else {
                mysqli_stmt_close($stmtFetchOld);
            }

            $updateQuery = "UPDATE dormer 
                            SET status = ?, dormerSurname = ?, dormerFirstname = ?, dormerMiddlename = ?, dormerNameExtension = ?, gender = ?, 
                                birthday = ?, collegeDept = ?, degreeProg = ?, yearLevel = ?, addressID = ?, cellphoneNumber = ?, 
                                telephoneNumber = ?, primaryEmailAdd = ?, secondEmailAdd = ?, dormEntryYear = ?, contactID = ?, requirements = ?
                            WHERE studentNumber = ?";
            $stmtUpdate = mysqli_prepare($conn, $updateQuery);
            mysqli_stmt_bind_param($stmtUpdate, "ssssssssssissssiiss", $status, $lastName, $firstName, $middleName, $nameExtension,
                $gender, $birthday, $colDept, $degProg, $yearLevel, $addressID, $cellNum, $telNum, $email, $secondEmail, $entryYear, 
                $contactID, $uploadReqFile, $studentNum);
            mysqli_stmt_execute($stmtUpdate);
            mysqli_stmt_close($stmtUpdate);
        } else {
            $insertDormerQuery = "INSERT INTO dormer (studentNumber, status, dormerSurname, dormerFirstname, dormerMiddlename, 
                                    dormerNameExtension, gender, birthday, collegeDept, degreeProg, yearLevel, addressID, cellphoneNumber, 
                                    telephoneNumber, primaryEmailAdd, secondEmailAdd, dormEntryYear, contactID, requirements)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmtInsert = mysqli_prepare($conn, $insertDormerQuery);
            mysqli_stmt_bind_param($stmtInsert, "sssssssssssissssiis", $studentNum, $status, $lastName, $firstName, $middleName, $nameExtension,
                $gender, $birthday, $colDept, $degProg, $yearLevel, $addressID, $cellNum, $telNum, $email, $secondEmail, $entryYear, $contactID, 
                $uploadReqFile);
            mysqli_stmt_execute($stmtInsert);
            mysqli_stmt_close($stmtInsert);
        }   
        
        header("Location: ".$_SERVER['PHP_SELF']."?success=3");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <link rel="stylesheet" href="../CSS/Requirement_Submission.css">
        <link rel="stylesheet" href="../CSS/navigation_bar.css">
        <link rel="stylesheet" href="../CSS/footer.css">

        <link rel="icon" type="image/x-icon" href="https://i.ibb.co/2nNpfB4/Untitled-design-24.png">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://kit.fontawesome.com/d5b4e20b91.js" crossorigin="anonymous"></script>
        <title>Application Forms</title>

    </head>
    <body>
        <!-- NAVBAR-->   
            <div class="header">
                <img id="homeLogo" src="https://i.ibb.co/2nNpfB4/Untitled-design-24.png" alt="">
                <div class="university-text">
                    <p class="up">University of the Philippines</p>
                    <p id="down">MINDANAO</p>
                </div>
                
                <nav class="desktop-nav">
                    <ul>
                        <li><a href="index.php">About</a></li>
                        <li><a href="../PHP/Requirements.php">Application</a></li>
                        <li><a href="../PHP/forms-option.php">Permit Forms</a></li>
                        
                    </ul>
                </nav>
            </div>
            <!--NAVBAR-->

        <?php if ($showMessageOnly == 3): ?>
            <div class="success-box">
                <p>Application submitted successfully</p>
                <a href="index.php">Go back to Home Page</a> <!--link to go back to home  -->
            </div>
        <?php elseif ($showMessageOnly == 2 && isset($_GET['msg'])): ?>
            <div class="error-box">
                <?php echo htmlspecialchars($_GET['msg']); ?>
                <a href="../PHP/index.php">Go back to Home Page</a> <!--link to go back to home  -->
            </div>
        <?php else: ?>
            <!--IMAGE-->
                <div class="image-for-application"></div>
            <!--IMAGE-->
            <!--Sumission Form-->
            <div class="bgshade">           <!--BG FOR THE DIV-->
                <div class="req-about">     <!--BOX FOR THR DIV-->
                    <form action="" method="POST" enctype="multipart/form-data">
                        <fieldset>
                            <h3>Application Form</h3>
                            <p class="important-note">IMPORTANT NOTE:<br>
                                The Republic Act (RA) you would use for confidential information in the Philippines is Republic Act No. 10173, 
                                also known as the Data Privacy Act of 2012. This law aims to protect individual personal information in information and 
                                communications systems. <br><br>
                                Everything state in this form will be treated with the UTMOST CONFIDENTIALITY.</p>
                            
                            <h4>Student Information</h4>
                            <div class="student-info-section">
                                <div>
                                    <label for="fname">First Name</label> <br>
                                    <input id="fname" type="text" placeholder="ex. John" name="fname" required>
                                </div>
                                <div>
                                    <label for="lname">Last Name</label> <br>
                                    <input id="lname" type="text" placeholder="ex. Doe" name="lname" required>
                                </div>
                                <div>
                                    <label for="mname">Middle Name</label> <br>
                                    <input id="mname" type="text" placeholder="ex. Dave" name="mname">
                                </div>
                                <div>
                                    <label for="suffix">Suffix</label> <br>
                                    <input id="suffix" type="text" placeholder="ex. Jr., if none leave blank" name="ename">
                                </div>
                                <div>
                                    <label for="birthdate">Date of Birth</label> <br>
                                    <input id="birthdate" type="date" placeholder="ex. YYYY/MM/DD" name="birthdate" required>
                                </div>
                                <div>
                                    <label for="gender">Sex assigned at Birth</label> <br>
                                            <select name="gender" id="gender">
                                                <option value="" disabled selected>Gender</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                </div>
                                <div>
                                    <label for="student-no">Student Number</label> <br>
                                    <input id="student-no" type="text" placeholder="ex. 2024-XXXXX" name="studentNum" required pattern="^\d{4}-\d{5}$">
                                </div>
                                <div>
                                    <label for="department">Department</label> <br>
                                            <select name="department" id="department">
                                                <option value="" disabled selected>Select Department</option>
                                                <option value="Architecture">Architecture</option>
                                                <option value="Human Kinetics">Human Kinetics</option>
                                                <option value="Humanities">Humanities</option>
                                                <option value="Social Science">Social Science</option>
                                                <option value="DBSES">Biological Sciences and Environmental Studies</option>
                                                <option value="DMPCS">Mathematics, Physics, and Computer Science</option>
                                                <option value="DFSC">Food Science and Chemistry</option>
                                                <option value="Management">Management</option>
                                            </select>
                                </div>
                                <div>
                                    <label for="course">Course</label> <br>
                                            <select name="degProg" id="course" required>
                                                <option value="" disabled selected>Select Course</option>
                                                <option value="AASS">AA Sports Studies</option>
                                                <option value="BACMA">BA Communications and Multimedia Arts</option>
                                                <option value="BAE">BA English</option>
                                                <option value="BSAE">BS Agribusiness Economics</option>
                                                <option value="BSAnthro">BS Anthropology</option>
                                                <option value="BSAM">BS Applied Mathematics</option>
                                                <option value="BSArchi">BA Architechture</option>
                                                <option value="BSB">BS Biology</option>
                                                <option value="BSCS">BS Computer Science</option>
                                                <option value="BSDS">BS Data Science</option>
                                                <option value="BSFT">BS Food Technology</option>
                                                <option value="BSSS">BS Sports Sciences</option> 
                                            </select>
                                </div>
                                <div>
                                    <label for="year-lvl">Year Level</label> <br>
                                            <select name="year-lvl" id="year-lvl" required>
                                                <option value="" disabled selected>Select Year</option>
                                                <option value="1st">1st Year</option>
                                                <option value="2nd">2nd Year</option>
                                                <option value="3rd">3rd Year</option>
                                                <option value="4th">4th Year</option>
                                                <option value="5th">5th Year</option>
                                            </select><br>
                                </div>
                            </div>
                            <h4>Permanent Address</h4><br>
                            <div class="address-section">
                                <div>
                                    <label for="bldg-no.">Floor no., Bldg. no., House no.</label> <br>
                                    <input id="bldg-no." type="text" name="lotNum">
                                </div>
                                <div>
                                    <label for="subdivision">Street, Bldg, Subdivision, Village</label> <br>
                                    <input id="subdivision" type="text" name="street">
                                </div>
                                <div>
                                    <label for="zipcode">Zipcode</label> <br>
                                    <input id="zipcode" type="number" name="zipcode">
                                </div>
                                <div>
                                    <label for="county">Country</label> <br>
                                    <input id="country" type="text" placeholder="ex. Philippines" name="country" required>
                                </div>
                                <div>
                                    <label for="region">Region</label> <br>
                                    <select name="region" id="region" required>
                                        <option value="" disabled selected>Select your region</option>
                                        <option value="Region 1">Region I</option>
                                        <option value="Region 2">Region II</option>
                                        <option value="Region 3">Region III</option>
                                        <option value="CALABARZON">Region IV-A</option>
                                        <option value="MIMAROPA">Region IV-B</option>
                                        <option value="Region 5">Region V</option>
                                        <option value="Region 6">Region VI</option>
                                        <option value="Region 7">Region VII</option>
                                        <option value="Region 8">Region VIII</option>
                                        <option value="Region 9">Region IX</option>
                                        <option value="Region 10">Region X</option>
                                        <option value="Region 11">Region XI</option>
                                        <option value="Region 12">Region XII</option>
                                        <option value="Region 13">Region XIII</option>
                                        <option value="NCR">NCR</option>
                                        <option value="CAR">CAR</option>
                                        <option value="BARMM">BARMM</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="city">City</label> <br>
                                    <input id="city" type="text" placeholder="ex. Davao City" name="city" required>
                                </div>
                                <div>
                                    <label for="baranggay">Baranggay</label> <br>
                                    <input id="baranggay" type="text" placeholder="ex. Dumoy" name="barangay" required>
                                </div>
                            </div><br>

                            <h4>Contact Information</h4>
                            <div class="contact-section">
                                <div>
                                    <label for="cellphone">Cellphone Number</label> <br>
                                    <input id="cellpohne" type="number" placeholder="ex. 09*********" name="cellphoneNum" required>
                                </div> 
                                <div>
                                    <label for="telephone">Telephone Number</label> <br>
                                    <input id="telepohne" type="number" placeholder="ex. (02) 123 4567" name="telphoneNum">
                                </div> 
                                <div>
                                    <label for="p-mail">Primary Email Address</label> <br>
                                    <input id="p-email" type="email" placeholder="ex. samplename.gmail.com" name="email" required>
                                </div>
                                <div>
                                    <label for="s-mail">Secondary Email Address</label> <br>
                                    <input id="s-email" type="email" placeholder="ex. samplename.gmail.com" name="email2">
                                </div><br>
                            </div>

                            <h4>Emergency Contact</h4>
                            <div class="emergency-contact-section">
                                <div>
                                    <label for="contactPersonF">First Name</label>
                                    <input type="text" id="contactPersonF" name="contactPersonF" required>
                                </div>

                                <div>
                                    <label for="contactPersonL">Last Name</label>
                                    <input type="text" id="contactPersonL" name="contactPersonL" required>
                                </div>

                                <div>
                                    <label for="contactNum">Contact Number</label>
                                    <input type="text" id="contactNum" name="contactNum" placeholder="09*********" required>
                                </div>
                            </div>

                            <h4>Requirement Submission Bin</h4>
                            <label class="submission" for="fileReq">Add/Drop items here....</label>
                            <input type="file" id="fileReq" name="requirements" accept=".pdf, .doc, .docx" required>
                            
                            <div class="lower-Buttons">
                                <a class="back" href="Requirements.php"><i class="fa-solid fa-arrow-left"></i></a>
                                <input class="submit-now" type="submit" value="Submit Now" name="submit">
                            </div>
                        </fieldset>
                    </form>
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
            <!-- FOOTER -->
                <?php endif; ?>
</body>
</html>