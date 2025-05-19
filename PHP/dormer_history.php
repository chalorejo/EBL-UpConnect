<?php
include 'connectDB.php';

$historyByStudent = [];

$query = "SELECT studentNumber, status, requirements, timestamp FROM dormer_history ORDER BY studentNumber, timestamp DESC";
$result = mysqli_query($conn, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $studentNum = $row['studentNumber'];
        if (!isset($historyByStudent[$studentNum])) {
            $historyByStudent[$studentNum] = [];
        }
        $historyByStudent[$studentNum][] = [
            'status' => $row['status'],
            'requirements' => $row['requirements'],
            'date' => $row['timestamp']
        ];
    }
} else {
    echo "<p>Error fetching dormer history: " . htmlspecialchars(mysqli_error($conn)) . "</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Dormer Application Histories</title>

    <!--CSS-->
    <link rel="stylesheet" href="../CSS/admin_view.css">
    <link rel="stylesheet" href="../CSS/navigation_bar.css">

    <link rel="icon" type="image/x-icon" href="https://i.ibb.co/2nNpfB4/Untitled-design-24.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/d5b4e20b91.js" crossorigin="anonymous"></script>

    <!--FONTS-->
    <link href="https://fonts.googleapis.com/css2?family=PT+Serif:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h2 { margin-top: 40px; }
        table { width: 80%; margin: 10px auto 30px auto; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        th { background-color: #f2f2f2; }
        .student-group { border: 1px solid #ddd; margin-bottom: 40px; padding: 10px; border-radius: 8px; }
        .student-header { background-color: #e6f7ff; padding: 10px; border-radius: 6px; font-weight: bold; }
        h3{ font-size: 3rem; color: #00573F; margin-bottom: 40px; font-family: 'Optima'; font-weight: normal; text-align: center;}
    </style>
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
                <li><a href="../PHP/dormer_history.php">Dormer History</a></li>
                <li><a href="../PHP/logout_process.php">Log Out</a></li>

            </ul>
        </nav>
    </div><br><br><br><br><br><br><br><br>
    <!--NAVBAR-->

    <h3>All Dormer Application Histories</h3>

    <?php if (count($historyByStudent) > 0): ?>
        <?php foreach ($historyByStudent as $studentNum => $histories): ?>
            <div class="student-group">
                <div class="student-header">Student Number: <?php echo htmlspecialchars($studentNum); ?></div>
                <table>
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Requirement File</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($histories as $history): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($history['status']); ?></td>
                                <td>
                                    <?php
                                    $filepath = htmlspecialchars($history['requirements']);
                                    if (file_exists($filepath)) {
                                        echo "<a href=\"$filepath\" target=\"_blank\">View</a>";
                                    } else {
                                        echo "<span style='color: red;'>File missing</span>";
                                    }
                                    ?>
                                </td>
                                <td><?php echo date('F j, Y, g:i A', strtotime($history['date'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No dormer application history found.</p>
    <?php endif; ?>

</body>
</html>