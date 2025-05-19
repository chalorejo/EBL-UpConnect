<?php
    // for log out logic
    session_start();
    session_unset();     // Clear all session variables
    session_destroy();   

    header("Location: admin_login.php");
    exit();
?>
