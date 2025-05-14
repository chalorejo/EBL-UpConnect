<?php
    // search logic

    $search = '';
    $whereClause = '';

    if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
        $search = mysqli_real_escape_string($conn, trim($_GET['search']));
        $whereClause = "WHERE dormer.studentNumber LIKE '%$search%' 
                        OR dormer.dormerSurname LIKE '%$search%' 
                        OR dormer.dormerFirstname LIKE '%$search%'
                        OR dormer.status LIKE '%$search%'";
    }
?>
