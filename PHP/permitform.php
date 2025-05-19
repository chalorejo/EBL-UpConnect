<?php
    include 'connectDB.php';
    $showMessageOnly = false;
    $message = isset($_GET['error']) ? urldecode($_GET['error']) : null;

    if (isset($_POST['submit'])) {
        $studentNum = mysqli_real_escape_string($conn, $_POST['studentNum']);
        $permitType = mysqli_real_escape_string($conn, $_POST['permit']);
        $fromDate = mysqli_real_escape_string($conn, $_POST['fromDate']);
        $toDate = mysqli_real_escape_string($conn, $_POST['toDate']);
        $reason = mysqli_real_escape_string($conn, $_POST['purpose']);

        $checkQuery = "SELECT status FROM dormer WHERE studentNumber = ?";
        if ($checkStmt = mysqli_prepare($conn, $checkQuery)) {
            mysqli_stmt_bind_param($checkStmt, "s", $studentNum);
            mysqli_stmt_execute($checkStmt);
            mysqli_stmt_store_result($checkStmt);

            if (mysqli_stmt_num_rows($checkStmt) > 0) {
                mysqli_stmt_bind_result($checkStmt, $status);
                mysqli_stmt_fetch($checkStmt);

                if (strtolower($status) === 'active') {
                    // Student is active, proceed with file upload and payment insert

                    $uploadDir = 'permit_signs/';
                    $uploadFileSign = $uploadDir . basename($_FILES['sign']['name']);
                    $imageFileType = strtolower(pathinfo($uploadFileSign, PATHINFO_EXTENSION));
                    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

                    // Check if type of file is allowed
                    if (in_array($imageFileType, $allowedTypes)) {
                        if (move_uploaded_file($_FILES['sign']['tmp_name'], $uploadFileSign)) {
                            // File uploaded successfully
                            $query = "INSERT INTO permit(studentNumber, permitType, fromDate, toDate, Reason, signPath)
                                VALUES (?, ?, ?, ?, ?, ?)";
                            
                            if ($stmt = mysqli_prepare($conn, $query)) {
                                mysqli_stmt_bind_param($stmt, "ssssss", $studentNum, $permitType, $fromDate, $toDate, $reason, $uploadFileSign);
                                mysqli_stmt_execute($stmt);
                                mysqli_stmt_close($stmt);

                                header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
                                exit;
                            } else {
                                header("Location: " . $_SERVER['PHP_SELF'] . "?error=" . urlencode("Insert query error: " . mysqli_error($conn)));
                                exit;
                            }
                        } else {
                            // student number doesn't exist
                            header("Location: " . $_SERVER['PHP_SELF'] . "?error=" . urlencode("Student number does NOT exist"));
                            exit;
                        }
                        mysqli_stmt_close($checkStmt);
                    } else {
                        header("Location: " . $_SERVER['PHP_SELF'] . "?error=" . urlencode("Insert query error: " . mysqli_error($conn)));
                        exit;
                    }
                } else {
                    header("Location: " . $_SERVER['PHP_SELF'] . "?error=" . urlencode("Student is not accepted yet and cannot make a payment."));
                    exit;
                }
            } else {
                // student number doesn't exist
                header("Location: " . $_SERVER['PHP_SELF'] . "?error=" . urlencode("Student number does NOT exist"));
                exit;
            }
            mysqli_stmt_close($checkStmt);
        } else {
            header("Location: " . $_SERVER['PHP_SELF'] . "?error=" . urlencode("Insert query error: " . mysqli_error($conn)));
            exit;
        }
    }
    $showMessageOnly = isset($_GET['success']) && $_GET['success'] == 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../CSS/Requirement_Submission.css">
        <link rel="stylesheet" href="../CSS/navigation_bar.css">
        <link rel="stylesheet" href="../CSS/footer.css">
        <link rel="stylesheet" href="../CSS/permit.css">
        <link rel="stylesheet" href="../CSS/Requirement_Submission.css">

        <link rel="icon" type="image/x-icon" href="https://i.ibb.co/2nNpfB4/Untitled-design-24.png">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://kit.fontawesome.com/d5b4e20b91.js" crossorigin="anonymous"></script>

    <title>Permit Form</title>
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
                        <li><a href="../PHP/index.php">About</a></li>
                        <li><a href="../PHP/Requirements.php">Application</a></li>
                        <li><a href="../PHP/forms-option.php">Permit Forms</a></li>
                        
                    </ul>
                </nav>
            </div>
    <!--NAVBAR-->

     <!--IMAGE-->
        <div class="image-for-application"></div>
    <!--IMAGE-->

    <?php if ($showMessageOnly): ?>
        <!-- design here Cha, this will show once na submit na -->
         <div class="submission-message">
            <p class="message-success">Permit request submitted successfully.</p>
            <p class="message-info">Please get your return slip at the office. Thank you</p>
            <div class="message-links">
                <a href="permitform.php" class="submit-now">Back to Permit Form</a>
                <a href="index.php" class="submit-now">Go to Home</a>
            </div>
         </div>
        
    <?php else: ?>
        <?php if (isset($message)): ?>
            <div class="error-box">
                <p><?php echo htmlspecialchars($message); ?></p>
            </div>
        <?php endif; ?>
        
        
        <div class ="bgshade">
        <div class ="req-about">
        <h3>Request for Overnight/Weekend Permit</h3>
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="studentNum">Student Number</label>
            <input type="text" name="studentNum" id="studentNum" required>

            <p>This is to request for</p>

            <div class="radio-group">
                <label>
                    <input type="radio" name="permit" value="Overnight Permit" required>
                    Overnight Permit
                </label>
                <label>
                    <input type="radio" name="permit" value="Weekend Permit">
                    Weekend Permit
                </label>
            </div>
            
            <label for="fromDate">from</label>
            <input type="date" name="fromDate" required>

            <label for="toDate">to</label>
            <input type="date" name="toDate" required> <br>

            <label for="purpose">Reason/Purpose for the said request</label>
            <textarea name="purpose" id="purpose" required></textarea>

            <p>Thank you.</p>

            <label for="sign" class="submission">Upload Signature over Printed Name</label>
            <input type="file" name="sign" id="sign" accept="image/*" required>
            <span id="filename-display" class="important-note"></span>

            <p>
                It is understood that by signing this permit I should come back at the dormitory on the date 
                specified, otherwise, I shall be sanctioned accoredingly. It has also come to my understanding 
                that I should hold full responsiility over my personal safety while I am outside the dormitory 
                premises.
            </p>

            <input type="submit" value="Submit" name="submit" class="submit-now">
        </form>
        </div>
        </div>
    <?php endif; ?>
</body>

<script>
        // to avoid resubmission after no student number match the input
        if (window.location.search.includes("error=")) {
            const url = new URL(window.location.href);
            url.searchParams.delete("error");
            window.history.replaceState({}, document.title, url.pathname);
        }

        document.getElementById('sign').addEventListener('change', function() {
            const fileName = this.files[0]?.name || "No file chosen";
            document.getElementById('filename-display').textContent = fileName;
        });

    </script>
</html>
