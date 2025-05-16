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
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];

        if (in_array($imageFileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['requirements']['tmp_name'], $uploadReqFile)) {
            } else {
                $errorMessage = "Error uploading the file.";
                header("Location: ".$_SERVER['PHP_SELF']."?success=2&msg=" . urlencode($errorMessage));
                exit;
            }
        } else {
            $errorMessage = "Invalid file type. Only JPG, PNG, GIF, and PDF are allowed.";
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
        <title>Application Form</title>
    </head>
    <body>
        <?php if ($showMessageOnly == 3): ?>
            <p>Application submitted successfully</p>
            <a href="home.html">Go back to Home Page</a> <!--link to go back to home  -->
        <?php elseif ($showMessageOnly == 2 && isset($_GET['msg'])): ?>
            <div class="error-box">
                <?php echo htmlspecialchars($_GET['msg']); ?>
                <a href="home.html">Go back to Home Page</a> <!--link to go back to home  -->
            </div>
        <?php else: ?>
            <form action="" method="POST">
                <!-- Student Basic Information -->
                <label for="fname">First Name</label>
                <input type="text" id="fname" name="fname" required>

                <label for="lname">Last Name</label>
                <input type="text" id="lname" name="lname" required>

                <label for="mname">Middle Name</label>
                <input type="text" id="mname" name="mname">

                <label for="ename">Suffix</label>
                <input type="text" id="ename" name="ename">

                <label for="birthdate">Date of Birth</label>
                <input type="date" id="birthdate" name="birthdate" required>

                <label for="gender">Gender</label>
                <select name="gender" id="gender" required>
                    <option value="Female">Female</option>
                    <option value="Male">Male</option>
                </select>

                <label for="studentNum">Student Number</label>
                <input type="text" name="studentNum" id="studentNum"
                        pattern="^\d{4}-\d{5}$"
                        placeholder="YYYY-12345"
                        required>

                <label for="department">Department</label>
                <select id="department" name="department">
                    <option value="" disabled selected>Choose your department</option>
                    <option value="Architecture">Architecture</option>
                    <option value="Human Kinetics">Human Kinetics</option>
                    <option value="Humanities">Humanities</option>
                    <option value="Social Science">Social Science</option>
                    <option value="DBSES">Biological Sciences and Environmental Studies</option>
                    <option value="DMPCS">Mathematics, Physics, and Computer Science</option>
                    <option value="DFSC">Food Science and Chemistry</option>
                    <option value="Management">Management</option>
                </select>

                <label for="degProg">Course</label>
                <select name="degProg" id="course" required>
                    <option value="" disabled selected>Choose your department</option>
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

                <label for="year">Year</label>
                <select name="year" id="year" required>
                    <option value="" disabled selected>Year level</option>
                    <option value="1st">1st Year</option>
                    <option value="2nd">2nd Year</option>
                    <option value="3rd">3rd Year</option>
                    <option value="4th">4th Year</option>
                    <option value="5th">5th Year</option>
                </select>

                <!-- Permanent Address -->
                <label for="lotNum">Floor no., Bldg. no., House no.</label>
                <input type="text" name="lotNum" id="lotNum">

                <label for="street">Street, Bldg, Subdivison, Village</label>
                <input type="text" name="street" id="street">

                <label for="zipcode">Zip Code</label>
                <input type="number" name="zipcode" id="zipcode">

                <label for="country">Country</label>
                <input type="text" name="country" id="country">

                <label for="region">Region</label>
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
        
                <label for="city">City</label>
                <input type="text" name="city" id="city" required>

                <label for="barangay">Barangay</label>
                <input type="text" name="barangay" id="barangay" required>

                <!-- Contact Information -->
                <label for="cellphoneNum">Cellphone Number</label>
                <input type="tel" id="cellphoneNum" name="cellphoneNum" required placeholder="09*********">

                <label for="telphoneNum">Telephone Number</label>
                <input type="tel" id="telphoneNum" name="telphoneNum">

                <label for="email">Primary Email Address</label>
                <input type="email" id="email" name="email">

                <label for="email2">Secondary Email Address</label>
                <input type="email" id="email2" name="email2">

                <!-- Contact person for mergencies -->
                <label for="contactPersonF">First Name</label>
                <input type="text" id="contactPersonF" name="contactPersonF" required>

                <label for="contactPersonL">Last Name</label>
                <input type="text" id="contactPersonL" name="contactPersonL" required>

                <label for="contactNum">Contact Number</label>
                <input type="text" id="contactNum" name="contactNum" placeholder="09*********" required>

                <label for="requirements">Requirement Submission Bin</label>
                <input type="file" id="requirements" name="requirements" accept="application/pdf" required>

                <input type="submit" value="Submit Now" name="submit">
            </form>
        <?php endif; ?>
    </body>
</html>
