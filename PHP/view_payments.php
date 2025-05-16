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
    $sql = "SELECT payment.*, dormer.dormerFirstname, dormer.dormerMiddlename, dormer.dormerSurname
            FROM payment
            JOIN dormer ON payment.studentNumber = dormer.studentNumber
            WHERE payment.studentNumber = ?";

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
    <title>Payment Records - <?php echo htmlspecialchars($studentNumber); ?></title>
</head>
<body>
    <a href="admin_view.php" class="back">← Back to Admin View</a> <!-- Ikaw na bahala asa nimo ni ibutang dapit -->
    <p>Student Name: <strong><?php echo htmlspecialchars($studentName); ?></strong></p>
    <p>Student Number: <strong><?php echo htmlspecialchars($studentNumber); ?></strong></p>


    <table>
        <tr>
            <th>Payment ID</th>
            <th>Amount</th>
            <th>Payment Purpose</th>
            <th>Date Paid</th>
            <th>Proof Payment</th>
        </tr>
        <?php if (count($rows) === 0): ?>
            <tr>
                <td colspan="5">No payment records found</td>
            </tr>
        <?php else: ?>
            <?php foreach ($rows as $row) {
                echo "<tr>";
                    echo "<td>" . $row['paymentID'] . "</td>";
                    echo "<td>" . $row['amount'] . "</td>";
                    echo "<td>" . $row['paymentFor'] . "</td>";
                    echo "<td>" . $row['date'] . "</td>";
                    echo "<td>";
                        if (!empty($row['paymentPicPath']) && file_exists($row['paymentPicPath'])) {
                            $filename = basename($row['paymentPicPath']);
                            echo '<a href="#" onclick="openModal(\'' . htmlspecialchars($row['paymentPicPath']) . '\'); return false;">' . htmlspecialchars($filename) . '</a>';
                        } else {
                            echo "No Image";
                        }
                    echo "</td>";
                echo "</tr>";
            } ?>
        <?php endif; ?>
    </table>

    <!-- Girllll kini na style for this para mugana tong effect na pina google drive -->
    <div id="imageModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); justify-content:center; align-items:center;">
        <span onclick="closeModal()" style="position:absolute; top:20px; right:30px; font-size:30px; color:white; cursor:pointer;">&times;</span>
        <img id="modalImage" src="" style="max-width:90%; max-height:90%;">
    </div>

</body>

<script>
    function openModal(imagePath) {
        document.getElementById('modalImage').src = imagePath;
        document.getElementById('imageModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('imageModal').style.display = 'none';
    }
</script>
</html>
