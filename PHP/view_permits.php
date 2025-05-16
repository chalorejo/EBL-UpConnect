<?php
    include 'connectDB.php';

    if (!isset($_GET['studentNumber'])) {
        die("Student number not specified.");
    }

    $studentNumber = $_GET['studentNumber'];

    // student name
    $namesql = "SELECT dormer.dormerFirstname, dormer.dormerMiddlename, dormer.dormerSurname
                FROM dormer
                WHERE studentNumber = ?";

    $stmtname = mysqli_prepare($conn, $namesql);
    mysqli_stmt_bind_param($stmtname, "s", $studentNumber);
    mysqli_stmt_execute($stmtname);
    $resultname = mysqli_stmt_get_result($stmtname);

    $studentName = "Unknown Student";
    if ($p = mysqli_fetch_assoc($resultname)) {
        $studentName = $p['dormerFirstname'] . ' ' . $p['dormerMiddlename'] . ' ' . $p['dormerSurname'];
    }

    // payment record
    $sql = "SELECT permit.*, dormer.dormerFirstname, dormer.dormerMiddlename, dormer.dormerSurname
            FROM permit
            JOIN dormer ON permit.studentNumber = dormer.studentNumber
            WHERE permit.studentNumber = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $studentNumber);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    
    mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Permit Records - <?php echo htmlspecialchars($studentNumber); ?></title>
</head>
<body>
    <a href="admin_view.php" class="back">← Back to Admin View</a> <!-- Ikaw na bahala asa nimo ni ibutang dapit -->
    <p>Student Name: <strong><?php echo htmlspecialchars($studentName); ?></strong></p>
    <p>Student Number: <strong><?php echo htmlspecialchars($studentNumber); ?></strong></p>


    <table>
        <tr>
            <th rowspan="2">Permit ID</th>
            <th rowspan="2">Permit Type</th>
            <th colspan="2">Dates</th>
            <th rowspan="2">Reason</th>
        </tr>
        <tr>
            <th>From</th>
            <th>To</th>
        </tr>
        <?php if (count($rows) === 0): ?>
            <tr>
                <td colspan="5">No payment records found</td>
            </tr>
        <?php else: ?>
            <?php foreach ($rows as $row) {
                echo "<tr>";
                    echo "<td>" . $row['permitNum'] . "</td>";
                    echo "<td>" . $row['permitType'] . "</td>";
                    echo "<td>" . $row['fromDate'] . "</td>";
                    echo "<td>" . $row['toDate'] . "</td>";
                    echo "<td>" . $row['Reason'] . "</td>";
                echo "</tr>";
            } ?>
        <?php endif; ?>
    </table>

</body>
</html>
