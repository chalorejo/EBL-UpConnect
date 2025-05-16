<?php
    include 'connectDB.php'; // connection to database
    $showMessageOnly = false;
    $message = isset($_GET['error']) ? urldecode($_GET['error']) : null;

    if(isset($_POST['submit'])) {
        $studentNum = mysqli_real_escape_string($conn, $_POST['studentNumber']);
        $paymentFor = mysqli_real_escape_string($conn, $_POST['payment_for']);
        $amount = mysqli_real_escape_string($conn, $_POST['amount']);
        $date = mysqli_real_escape_string($conn, $_POST['date']);

        // check if the student is in the record
        $checkQuery = "SELECT 1 FROM dormer WHERE studentNumber = ?";
        if ($checkStmt = mysqli_prepare($conn, $checkQuery)) {
            mysqli_stmt_bind_param($checkStmt, "s", $studentNum);
            mysqli_stmt_execute($checkStmt);
            mysqli_stmt_store_result($checkStmt);
    
            if (mysqli_stmt_num_rows($checkStmt) > 0) {
                // Student exists, insert payment

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
        <title>Payment</title>
    </head>
    <body>
        <?php if ($showMessageOnly): ?>
            <!-- design here Cha, this will show once na submit na -->
            <p class="message">Payment record added successfully.</p>
            <a href="paymentform.php">Payment Form</a> <!-- dapat naay link to go back to payment or ikaw bahala kung naa ni or wala  -->
            <a href="home.html">Home</a> <!-- dapat naay link to go back to home  -->
        <?php else: ?>
            <?php if (isset($message)): ?>
                <p><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>

            <form action="" method="post" enctype="multipart/form-data">
                <!-- student number nalang ang input, no need for name and other info-->
                <label for="studentnumber">STUDENT NUMBER</label> <br>
                <input id="studentnumber" type="text" name="studentNumber" required>

                <!--payment for-->
                <label>Purpose of payment</label><br>
                <input type="checkbox" id="rent" name="payment" value="Monthly Rent" onchange="updatePaymentValue()">
                <label for="rent">Monthly Rent</label><br>

                <input type="checkbox" id="appliances" name="payment" value="Other Appliances" onchange="updatePaymentValue()">
                <label for="appliances">Other Appliances</label><br>

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
