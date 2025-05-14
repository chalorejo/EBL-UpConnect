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
            ORDER BY studentNumber";

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

    <h2><a href="admin_view.php">Student Records</a></h2>
    
    <?php include 'search_bar.php'; ?> <!-- the search bar here... -->

    <?php if ($noResults): ?>
        <p>No matching records found for "<?php echo htmlspecialchars($search); ?>".</p>
        <a href="admin_view.php" class="back">← Go Back</a> <!-- Ikaw na bahala sa words diri, diri sad if naa pakay i add lain when walay magmatch for thats search -->
    <?php else: ?>
        <table>
            <tr>
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
                    <td class="action">
                        <a href="view_payments.php?studentNumber=<?php echo $row['studentNumber']; ?>">Payments</a>
                        <a href="view_permits.php?studentNumber=<?php echo $row['studentNumber']; ?>">Permits</a>
                        <a href="update_dormer_information.php?studentNumber=<?php echo $row['studentNumber']; ?>">Update</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    <?php endif; ?>

    <?php mysqli_close($conn); ?>
</body>
</html>
