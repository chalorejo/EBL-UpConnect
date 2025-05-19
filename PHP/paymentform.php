<?php
    include 'connectDB.php'; // connection to database
    $showMessageOnly = false;
    $message = isset($_GET['error']) ? urldecode($_GET['error']) : null;

    if (isset($_POST['submit'])) {
        $studentNum = mysqli_real_escape_string($conn, $_POST['studentNumber']);
        $paymentFor = mysqli_real_escape_string($conn, $_POST['payment_for']);
        $amount = mysqli_real_escape_string($conn, $_POST['amount']);
        $date = mysqli_real_escape_string($conn, $_POST['date']);

        // check if the student exists and is active
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

                    $uploadDir = 'payment_photos/';
                    $uploadFile = $uploadDir . basename($_FILES['payment_screenshot']['name']);
                    $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
                    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

                    // Check if type of file is allowed
                    if (in_array($imageFileType, $allowedTypes)) {
                        if (move_uploaded_file($_FILES['payment_screenshot']['tmp_name'], $uploadFile)) {
                            // File uploaded successfully
                            $query = "INSERT INTO payment(studentNumber, paymentFor, amount, date, paymentPicPath)
                                      VALUES (?, ?, ?, ?, ?)";

                            if ($stmt = mysqli_prepare($conn, $query)) {
                                mysqli_stmt_bind_param($stmt, "ssiss", $studentNum, $paymentFor, $amount, $date, $uploadFile);
                                mysqli_stmt_execute($stmt);
                                mysqli_stmt_close($stmt);

                                header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
                                exit;
                            } else {
                                header("Location: " . $_SERVER['PHP_SELF'] . "?error=" . urlencode("Insert query error: " . mysqli_error($conn)));
                                exit;
                            }
                        } else {
                            header("Location: " . $_SERVER['PHP_SELF'] . "?error=" . urlencode("Failed to upload file."));
                            exit;
                        }
                    } else {
                        header("Location: " . $_SERVER['PHP_SELF'] . "?error=" . urlencode("Invalid file type. Only JPG, JPEG, PNG, GIF allowed."));
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
            header("Location: " . $_SERVER['PHP_SELF'] . "?error=" . urlencode("Query error: " . mysqli_error($conn)));
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
        <link rel="stylesheet" href="../CSS/paymentform.css">

        <link rel="icon" type="image/x-icon" href="https://i.ibb.co/2nNpfB4/Untitled-design-24.png">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://kit.fontawesome.com/d5b4e20b91.js" crossorigin="anonymous"></script>
        
        <title>Payment Form</title>
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
            <div class="success-box">
                <p class="message">Payment record added successfully.</p>
                <a href="paymentform.php">Payment Form</a> <!-- dapat naay link to go back to payment or ikaw bahala kung naa ni or wala  -->
                <a href="index.php">Home</a> <!-- dapat naay link to go back to home  -->
            </div>
            
        <?php else: ?>
            <?php if (isset($message)): ?>
                <div class="error-box">
                    <p><?php echo htmlspecialchars($message); ?></p>
                </div>
            <?php endif; ?>

            <div class="bgshade">      
            <div class="req-about"> 
            <form action="" method="post" enctype="multipart/form-data">
                <!-- student number nalang ang input, no need for name and other info-->
                <label for="studentnumber">STUDENT NUMBER</label> <br>
                <input id="studentnumber" type="text" name="studentNumber" required>

                <!--payment for-->
                <label>Purpose of payment</label><br>
                <div class="checkbox-group">
                    <input type="checkbox" id="rent" name="payment" value="Monthly Rent" onchange="updatePaymentValue()">
                    <label for="rent">Monthly Rent</label>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="appliances" name="payment" value="Other Appliances" onchange="updatePaymentValue()">
                    <label for="appliances">Other Appliances</label>
                </div>


                <!-- Hidden input that will store the final value -->
                <input type="hidden" name="payment_for" id="payment_for" required>

                <!-- amount -->
                <label for="amount">Amount</label>
                <input type="number" id="amount" name="amount">

                <!-- date -->
                <label for="date">Date</label>
                <input type="date" id="date" name="date" required>

                <!-- payment screenshot -->
                <label class="submission" for="fileReq">Payment Screenshot</label>
                <input id="fileReq" type="file" name="payment_screenshot" required>

                <input type="submit" value="Submit" name="submit">
            </form>
            </div>
            </div>

        <?php endif; ?>
    </body>

    <script>
        // for paymentFor value
        function updatePaymentValue() {
            const rent = document.getElementById('rent').checked;
            const appliances = document.getElementById('appliances').checked;
            const paymentFor = document.getElementById('payment_for');

            if (rent && appliances) {
                paymentFor.value = 'Both';
            } else if (rent) {
                paymentFor.value = 'Monthly Rent';
            } else if (appliances) {
                paymentFor.value = 'Other Appliances';
            } else {
                paymentFor.value = '';
            }
        }

        // to avoid resubmission after no student number match the input
        if (window.location.search.includes("error=")) {
            const url = new URL(window.location.href);
            url.searchParams.delete("error");
            window.history.replaceState({}, document.title, url.pathname);
        }
    </script>
</html>
