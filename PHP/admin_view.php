<?php
    include 'connectDB.php';
    include 'search.php';

    $sql = "SELECT dormer.*, address.country, address.region, address.city, address.barangay, address.region, 
                    address.subdivision_Street, address.lotNumber_Prk, address.zipCode, contact.contactLastName, 
                    contact.contactFirstName, contact.contactNumber
            FROM dormer
            JOIN address ON dormer.addressID = address.addressID 
            JOIN contact ON dormer.contactID = contact.contactID
            $whereClause
            ORDER BY studentNumber DESC";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    $noResults = mysqli_num_rows($result) === 0;
?>

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

    <!-- interanl css since there is conflict if external -->
    <style>
        body {
            overflow-y: hidden; 
            height: 100vh;       
        }

                .table-container {
            overflow-x: auto;
            overflow-y: auto;
            max-height: 80vh;
            margin: 10px 20px;
            border: 1px solid #ccc;
            height: calc(100vh - 200px);
        }

        table {
            border-collapse: collapse;
            width: 100%;
            min-width: 1500px;
            table-layout: auto;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ccc;
            background-color: #fff;
            white-space: nowrap;
        }

        .table-header th {
            position: sticky;
            top: 0;
            background-color: #f2f2f2;
            z-index: 10; 
        }

        th:nth-child(1),
        td:nth-child(1) {
            position: sticky;
            left: 0;
            background-color: #f9f9f9;
            z-index: 20; 
            width: 250px;
        }

        th:nth-child(2),
        td:nth-child(2) {
            position: sticky;
            left: 145px; 
            background-color: #f9f9f9;
            z-index: 20;
            width: 150px;
        }

        th:last-child,
        td:last-child {
            position: sticky;
            right: 0;
            background-color: #f9f9f9;
            z-index: 20;
            min-width: 180px;
            padding-left: 20px;
        }

        td.action a {
            display: inline-block;
            margin-right: 6px;
            padding: 4px 8px;
            background-color: #4CAF50;
            color: white;
            border-radius: 4px;
            text-decoration: none;
            font-size: 12.5px;
        }

        td.action a:hover {
            background-color:rgb(39, 94, 42);
        }

        tr:hover td {
            background-color: #f1f1f1;
        }

        .back {
            display: inline-block;
            margin: 20px;
            padding: 10px 16px;
            font-size: 14px;
            color: #fff;
            background-color: #666666;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.2s ease;
        }

        .back:hover {
            background-color: #00573F;
        }

        .error {
            color: #c62828;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin: 30px auto 10px auto;
        }

    </style>

</head>
<body>
    <?php if (isset($_GET['updated']) && $_GET['updated'] == 1): ?>
        <script>
            alert("Dormer information has been successfully updated.");

            if (history.replaceState) {
                const url = new URL(window.location);
                url.searchParams.delete('updated');
                history.replaceState(null, '', url.toString());
            }
        </script>
    <?php endif; ?>
    
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
                <li><a href="../PHP/permit_records.php">Permit Records</a></li>
                <li><a href="dormer_history.php">Dormer History</a></li>
                <li><a href="../PHP/logout_process.php">Log Out</a></li>
            </ul>
        </nav>
    </div><br><br><br><br><br>
    <!--NAVBAR-->

    <?php include 'search_bar.php'; ?>

    <?php if ($noResults): ?>
        <p class="error">No matching records found for "<?php echo htmlspecialchars($search); ?>".</p>
        <a href="admin_view.php" class="back">Go Back</a> <!-- Ikaw na bahala sa words diri -->
    <?php else: ?>
        <div class="table-container">
            <table>
                <tr class="table-header">
                    <th>Student Number</th>
                    <th>Status</th>
                    <th>Surname</th>    
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Name Extension</th>
                    <th>Gender</th>
                    <th>Birthday</th>
                    <th>Address</th>
                    <th>College/Department</th>
                    <th>Degree Program</th>
                    <th>Year Level</th>
                    <th>Cellphone Number</th>
                    <th>Telephone Number</th>
                    <th>Primary Email Address</th>
                    <th>Secondary Email Address</th>
                    <th>Contact Person</th>
                    <th>Emergency Contact Number</th>
                    <th>Dorm Entry Year</th>
                    <th>Dorm Exit Year</th>
                    <th>Requirement File Path</th>
                    <th>Records</th>
                </tr>
                <?php while($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['studentNumber']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td><?php echo $row['dormerSurname']; ?></td>
                        <td><?php echo $row['dormerFirstname']; ?></td>
                        <td><?php echo $row['dormerMiddlename']; ?></td>
                        <td><?php echo $row['dormerNameExtension']; ?></td>
                        <td><?php echo $row['gender']; ?></td>
                        <td><?php echo $row['birthday']; ?></td>
                        <td><?php echo $row['lotNumber_Prk'] . ', ' . $row['subdivision_Street'] . ', ' . $row['barangay'] . ', ' . $row['city'] . 
                                ', ' . $row['region'] . ', ' . $row['country'] . ', ' . $row['zipCode']; ?></td>
                        <td><?php echo $row['collegeDept']; ?></td>
                        <td><?php echo $row['degreeProg']; ?></td>
                        <td><?php echo $row['yearLevel']; ?></td>
                        <td><?php echo $row['cellphoneNumber']; ?></td>
                        <td><?php echo $row['telephoneNumber']; ?></td>
                        <td><?php echo $row['primaryEmailAdd']; ?></td>
                        <td><?php echo $row['secondEmailAdd']; ?></td>
                        <td><?php echo $row['contactFirstName'] . ' ' . $row['contactLastName']; ?></td>
                        <td><?php echo $row['contactNumber']; ?></td>
                        <td><?php echo $row['dormEntryYear']; ?></td>
                        <td><?php echo $row['dormExitYear']; ?></td>
                        <td>
                            <?php 
                                if (!empty($row['requirements']) && file_exists($row['requirements'])) {
                                    $filename = basename($row['requirements']);
                                    echo '<a href="' . htmlspecialchars($row['requirements']) . '" target="_blank">' . htmlspecialchars($filename) . '</a>';
                                } else {
                                    echo "No File";
                                }
                            ?>
                        </td>
                        <td class="action">
                            <a href="view_payments.php?studentNumber=<?php echo $row['studentNumber']; ?>">Payments</a>
                            <a href="view_permits.php?studentNumber=<?php echo $row['studentNumber']; ?>">Permits</a>
                            <a href="update_dormer_information.php?studentNumber=<?php echo $row['studentNumber']; ?>">Update</a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    <?php endif; ?>

    <?php mysqli_close($conn); ?>
</body>

<div id="overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;" onclick="closeModal()"></div>
<div id="myModal" style="display:none; position:fixed; top:10%; left:50%; transform:translateX(-50%); background:#fff; padding:20px; width:80%; max-width:600px; z-index:1001;">
    <span onclick="closeModal()" style="float:right; cursor:pointer;">&times; Close</span><br><br>
    <iframe id="modalFrame" style="width:100%; height:500px; border:none;"></iframe>
</div>

<script>
function openModal(filePath) {
    document.getElementById('modalFrame').src = filePath;
    document.getElementById('myModal').style.display = 'block';
    document.getElementById('overlay').style.display = 'block';
}

function closeModal() {
    document.getElementById('modalFrame').src = '';
    document.getElementById('myModal').style.display = 'none';
    document.getElementById('overlay').style.display = 'none';
}
</script>

</html>
