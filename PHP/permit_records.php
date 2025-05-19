<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin View</title>

    <!--CSS-->
    <link rel="stylesheet" href="../CSS/navigation_bar.css">

    <link rel="icon" type="image/x-icon" href="https://i.ibb.co/2nNpfB4/Untitled-design-24.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/d5b4e20b91.js" crossorigin="anonymous"></script>

    <!--FONTS-->
    <link href="https://fonts.googleapis.com/css2?family=PT+Serif:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/permit_records.css">
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
                    <li><a href="../PHP/admin_view.php">Student Records</a></li>
                    <li><a href="dormer_history.php">Dormer History</a></li>
                    <li><a href="../PHP/logout_process.php">Log Out</a></li>
                </ul>
            </nav>
        </div><br><br><br><br>
    <!--NAVBAR-->

    <h2>All Permit Record</h2>
    
    <?php
        include 'connectDB.php';

        $sql = "
            SELECT permit.*, dormer.dormerSurname, dormerFirstname,
                    CONCAT(dormer.dormerSurname, ', ', dormer.dormerFirstname) AS studentName
            FROM permit
            LEFT JOIN dormer ON permit.studentNumber = dormer.studentNumber
            ORDER BY permit.fromDate DESC, studentName ASC
        ";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $groupedData = [];

            while ($row = $result->fetch_assoc()) {
                $fromDate = $row['fromDate'];
                $groupedData[$fromDate][] = $row;
            }

            // Display grouped results
            foreach ($groupedData as $date => $permits) {
                echo "<h3>Date: " . htmlspecialchars($date) . "</h3>";
                echo "<table border='1' cellpadding='5' cellspacing='0'>";
                echo "<tr>
                        <th>Permit #</th>
                        <th>Student #</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Reason</th>
                    </tr>";
                
                foreach ($permits as $permit) {
                    echo "<tr>
                            <td>" . htmlspecialchars($permit['permitNum']) . "</td>
                            <td>" . htmlspecialchars($permit['studentNumber']) . "</td>
                            <td>" . htmlspecialchars($permit['studentName']) . "</td>
                            <td>" . htmlspecialchars($permit['permitType']) . "</td>
                            <td>" . htmlspecialchars($permit['fromDate']) . "</td>
                            <td>" . htmlspecialchars($permit['toDate']) . "</td>
                            <td>" . htmlspecialchars($permit['Reason']) . "</td>
                        </tr>";
                }
                
                echo "</table><br>";
            }
        } else {
            echo "No records found.";
        }

        $conn->close();
    ?>
</body>
</html>