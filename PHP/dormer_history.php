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
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h2 { margin-top: 40px; }
        table { width: 80%; margin: 10px auto 30px auto; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        th { background-color: #f2f2f2; }
        .student-group { border: 1px solid #ddd; margin-bottom: 40px; padding: 10px; border-radius: 8px; }
        .student-header { background-color: #e6f7ff; padding: 10px; border-radius: 6px; font-weight: bold; }
    </style>
</head>
<body>
    <h1>All Dormer Application Histories</h1>

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
