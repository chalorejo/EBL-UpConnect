<?php
    include 'connectDB.php';

    if (isset($_GET['studentNumber'])) {
        $studentNumber = htmlspecialchars($_GET['studentNumber']);
    } else {
        die("No student number provided.");
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
        $status = $_POST['status'];
        $firstName = $_POST['fname'];
        $lastName = $_POST['lname'];
        $middleName = $_POST['mname'];
        $nameExtension = $_POST['ename'];
        $gender = $_POST['gender'];
        $colDept = $_POST['department'];
        $degProg = $_POST['degProg'];
        $yearLevel = $_POST['year'];
        $lotNumber = $_POST['lotNum'];
        $street = $_POST['street'];
        $zipcode = $_POST['zipcode'];
        $country = $_POST['country'];
        $region = $_POST['region'];
        $city = $_POST['city'];
        $barangay = $_POST['barangay'];
        $cellNum = $_POST['cellphoneNum'];
        $telNum = $_POST['telphoneNum'];
        $email = $_POST['email'];
        $email2 = $_POST['email2'];
        $contactFname = $_POST['contactPersonF'];
        $contactLname = $_POST['contactPersonL'];
        $contactNum = $_POST['contactNum'];
        $yearStart = $_POST['yearStart'];
        $yearEnd = $_POST['yearEnd'];

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

        $insertDormerQuery = "UPDATE dormer 
                            SET status = ?,
                                dormerSurname = ?,
                                dormerFirstname = ?,
                                dormerMiddlename = ?,
                                dormerNameExtension = ?,
                                gender = ?,  
                                collegeDept = ?, 
                                degreeProg = ?, 
                                yearLevel = ?, 
                                addressID = ?, 
                                cellphoneNumber = ?, 
                                telephoneNumber = ?, 
                                primaryEmailAdd = ?, 
                                secondEmailAdd = ?, 
                                dormEntryYear = ?, 
                                dormExitYear = ?, 
                                contactID = ?
                                WHERE studentNumber = ?";

        if ($stmtInsert = mysqli_prepare($conn, $insertDormerQuery)) {
            mysqli_stmt_bind_param($stmtInsert, "sssssssssissssiiis", 
                $status, $lastName, $firstName, $middleName, $nameExtension,
                $gender, $colDept, $degProg, $yearLevel, 
                $addressID, $cellNum, $telNum, $email, $email2, 
                $yearStart, $yearEnd, $contactID, $studentNumber
            );
            mysqli_stmt_execute($stmtInsert);
            mysqli_stmt_close($stmtInsert);
        } else {
            die("Update failed: " . mysqli_error($conn));
        }
        
        header("Location: admin_view.php?updated=1");
        exit;

    }

    $sql = "SELECT dormer.*, address.country, address.region, address.city, address.barangay, address.subdivision_Street, address.lotNumber_Prk, 
                    address.zipCode, contact.contactLastName, contact.contactFirstName, contact.contactNumber
            FROM dormer
            JOIN address ON dormer.addressID = address.addressID
            JOIN contact ON dormer.contactID = contact.contactID
            WHERE dormer.studentNumber = '$studentNumber'
            LIMIT 1";

    $result = mysqli_query($conn, $sql);

    if (!$result || mysqli_num_rows($result)=== 0) {
        die("Dormer not found");
    }

    $row = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Update</title>
    </head>
    <body>
        <h2>Dormer Information</h2>
        <p>Student Number: <strong><?php echo htmlspecialchars($row['studentNumber']); ?></strong></p>

        <!-- Basic Information -->
        <form action="" method="POST" id="updateForm">
            <div>
                <label for="status">Status</label>
                <select name="status" id="status">
                    <option value="Active" <?php if($row['status'] === 'Active') echo 'selected'; ?>>Active</option>
                    <option value="Evicted" <?php if($row['status'] === 'Evicted') echo 'selected'; ?>>Evicted</option>
                    <option value="Pending" <?php if($row['status'] === 'Pending') echo 'selected'; ?>>Pending</option>
                    <option value="Rejected" <?php if($row['status'] === 'Rejected') echo 'selected'; ?>>Rejected</option>
                    <option value="Withdrawn" <?php if($row['status'] === 'Withdrawn') echo 'selected'; ?>>Withdrawn</option>
                </select>
            </div>

            <div>
                <label for="yearStart">Dorm Entry Year</label>
                <input type="int" id="yearStart" name="yearStart" value="<?php echo htmlspecialchars($row['dormEntryYear']); ?>">
            </div>

            <div>
                <label for="yearEnd">Dorm Exit Year</label>
                <input type="int" id="yearEnd" name="yearEnd" value="<?php echo htmlspecialchars($row['dormExitYear']); ?>">
            </div>

            <div>
                <label for="fname">First Name</label>
                <input type="text" id="fname" name="fname" required value="<?php echo htmlspecialchars($row['dormerFirstname']); ?>">
            </div>

            <div>
                <label for="lname">Last Name</label>
                <input type="text" id="lname" name="lname" required value="<?php echo htmlspecialchars($row['dormerSurname']); ?>">
            </div>
                
            <div>
                <label for="mname">Middle Name</label>
                <input type="text" id="mname" name="mname" value="<?php echo htmlspecialchars($row['dormerMiddlename']); ?>">
            </div>

            <div>
                <label for="ename">Suffix</label>
                <input type="text" id="ename" name="ename" value="<?php echo htmlspecialchars($row['dormerNameExtension']); ?>">
            </div>

            <div>
                <p>Birthday: <?php echo htmlspecialchars($row['birthday']); ?></p>
            </div>

            <div>
                <label for="gender">Gender</label>
                <select name="gender" id="gender" required>
                    <option value="Female" <?php if($row['gender'] === 'Female') echo 'selected'; ?>>Female</option>
                    <option value="Male"<?php if($row['gender'] === 'Male') echo 'selected'; ?>>Male</option>
                </select>
            </div>
             
            <div>
                <label for="department">Department</label>
                <select id="department" name="department">
                    <option value="Architecture" <?php if($row['collegeDept'] === 'Architecture') echo 'selected'; ?>>Architecture</option>
                    <option value="Human Kinetics" <?php if($row['collegeDept'] === 'Human Kinetics') echo 'selected'; ?>>Human Kinetics</option>
                    <option value="Humanities" <?php if($row['collegeDept'] === 'Humanities') echo 'selected'; ?>>Humanities</option>
                    <option value="Social Science" <?php if($row['collegeDept'] === 'Social Science') echo 'selected'; ?>>Social Science</option>
                    <option value="DBSES" <?php if($row['collegeDept'] === 'DBSES') echo 'selected'; ?>>Biological Sciences and Environmental Studies</option>
                    <option value="DMPCS" <?php if($row['collegeDept'] === 'DMPCS') echo 'selected'; ?>>Mathematics, Physics, and Computer Science</option>
                    <option value="DFSC" <?php if($row['collegeDept'] === 'DFSC') echo 'selected'; ?>>Food Science and Chemistry</option>
                    <option value="Management" <?php if($row['collegeDept'] === 'Management') echo 'selected'; ?>>Management</option>
                </select>
            </div>
                
            <div>
                <label for="degProg">Course</label>
                <input type="text" name="degProg" id="degProg" required value="<?php echo htmlspecialchars($row['degreeProg']); ?>">
            </div>

            <div>
                <label for="year">Year</label>
                <select name="year" id="year" required>
                    <option value="1st" <?php if($row['yearLevel'] === '1st') echo 'selected'; ?>>1st Year</option>
                    <option value="2nd" <?php if($row['yearLevel'] === '2nd') echo 'selected'; ?>>2nd Year</option>
                    <option value="3rd" <?php if($row['yearLevel'] === '3rd') echo 'selected'; ?>>3rd Year</option>
                    <option value="4th" <?php if($row['yearLevel'] === '4th') echo 'selected'; ?>>4th Year</option>
                    <option value="5th" <?php if($row['yearLevel'] === '5th') echo 'selected'; ?>>5th Year</option>
                </select>
            </div>
                
            <!-- Permanent Address -->
            <div>
                <label for="lotNum">Floor no., Bldg. no., House no.</label>
                <input type="text" name="lotNum" id="lotNum" value="<?php echo htmlspecialchars($row['lotNumber_Prk']); ?>">
            </div>
                
            <div>
                <label for="street">Street, Bldg, Subdivison, Village</label>
                <input type="text" name="street" id="street" value="<?php echo htmlspecialchars($row['subdivision_Street']); ?>">
            </div>

            <div>
                <label for="zipcode">Zip Code</label>
                <input type="number" name="zipcode" id="zipcode" value="<?php echo htmlspecialchars($row['zipCode']); ?>">
            </div>
                
            <div>
                <label for="country">Country</label>
                <input type="text" name="country" id="country" value="<?php echo htmlspecialchars($row['country']); ?>">
            </div>
            
            <div>
                <label for="region">Region</label>
                <select name="region" id="region" required>
                    <option value="1" <?php if($row['region'] === '1') echo 'selected'; ?>>Region I</option>
                    <option value="2" <?php if($row['region'] === '2') echo 'selected'; ?>>Region II</option>
                    <option value="3" <?php if($row['region'] === '3') echo 'selected'; ?>>Region III</option>
                    <option value="CALABARZON" <?php if($row['region'] === 'CALABARZON') echo 'selected'; ?>>Region IV-A</option>
                    <option value="MIMAROPA" <?php if($row['region'] === 'MIMAROPA') echo 'selected'; ?>>Region IV-B</option>
                    <option value="5" <?php if($row['region'] === '5') echo 'selected'; ?>>Region V</option>
                    <option value="6" <?php if($row['region'] === '6') echo 'selected'; ?>>Region VI</option>
                    <option value="7" <?php if($row['region'] === '7') echo 'selected'; ?>>Region VII</option>
                    <option value="8" <?php if($row['region'] === '8') echo 'selected'; ?>>Region VIII</option>
                    <option value="9" <?php if($row['region'] === '9') echo 'selected'; ?>>Region IX</option>
                    <option value="10" <?php if($row['region'] === '10') echo 'selected'; ?>>Region X</option>
                    <option value="11" <?php if($row['region'] === '11') echo 'selected'; ?>>Region XI</option>
                    <option value="12" <?php if($row['region'] === '12') echo 'selected'; ?>>Region XII</option>
                    <option value="13" <?php if($row['region'] === '13') echo 'selected'; ?>>Region XIII</option>
                    <option value="NCR" <?php if($row['region'] === 'NCR') echo 'selected'; ?>>NCR</option>
                    <option value="CAR" <?php if($row['region'] === 'CAR') echo 'selected'; ?>>CAR</option>
                    <option value="BARMM" <?php if($row['region'] === 'BARMM') echo 'selected'; ?>>BARMM</option>
                </select>
            </div>

            <div>
                <label for="city">City</label>
                <input type="text" name="city" id="city" required value="<?php echo htmlspecialchars($row['city']); ?>">
            </div>

            <div>
                <label for="barangay">Barangay</label>
                <input type="text" name="barangay" id="barangay" required value="<?php echo htmlspecialchars($row['barangay']); ?>">
            </div>

            <!-- Contact Information -->
            <div>
                <label for="cellphoneNum">Cellphone Number</label>
                <input type="tel" id="cellphoneNum" name="cellphoneNum" required value="<?php echo htmlspecialchars($row['cellphoneNumber']); ?>">
            </div>             

            <div>
                <label for="telphoneNum">Telephone Number</label>
                <input type="tel" id="telphoneNum" name="telphoneNum" value="<?php echo htmlspecialchars($row['telephoneNumber']); ?>">
            </div>   

            <div>
                <label for="email">Primary Email Address</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($row['primaryEmailAdd']); ?>">
            </div>    

            <div>
                <label for="email2">Secondary Email Address</label>
                <input type="email" id="email2" name="email2" value="<?php echo htmlspecialchars($row['secondEmailAdd']); ?>">
            </div>

            <!-- Contact person for mergencies -->
            <div>
                <label for="contactPersonF">First Name</label>
                <input type="text" id="contactPersonF" name="contactPersonF" value="<?php echo htmlspecialchars($row['contactFirstName']); ?>">
            </div>

            <div>
                <label for="contactPersonL">Last Name</label>
                <input type="text" id="contactPersonL" name="contactPersonL" value="<?php echo htmlspecialchars($row['contactLastName']); ?>">
            </div>    

            <div>
                <label for="contactNum">Contact Number</label>
                <input type="text" id="contactNum" name="contactNum" value="<?php echo htmlspecialchars($row['contactNumber']); ?>">
            </div>

            <a href="admin_view.php"><</a> <!-- like button to go back sa admin_view, i-kinda same tong button anto last time -->

            <input type="submit" value="Update" name="submit" onclick="confirmUpdate()">
        </form>

        <script>
            function confirmUpdate() {
                const confirmation = confirm("Are you sure you want to update the information?");
                if (!confirmation) {
                    event.preventDefault();  // Prevent form submission if canceled
                    return false; 
                }
                return true;  // Allow form submission if confirmed
            }
        </script>
    </body>
</html>
