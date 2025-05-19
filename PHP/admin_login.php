<?php
    session_start();
    $error = isset($_GET['error']) ? $_GET['error'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--CSS-->
    <link rel="stylesheet" href="../CSS/login_page.css">

    <link rel="icon" type="image/x-icon" href="https://i.ibb.co/2nNpfB4/Untitled-design-24.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/d5b4e20b91.js" crossorigin="anonymous"></script>

    <title>EBL UpConnect Login</title>
</head>
<body>
    <?php if ($error): ?> <!-- di ko sure kung diri ni na side ibutang or somewhere -->
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <div class="university-text">
        <img id="homeLogo" src="https://i.ibb.co/2nNpfB4/Untitled-design-24.png" alt=""> 
        <div>
            <p class="up">University of the Philippines</p>
            <p id="down">MINDANAO</p>
        </div>
    </div>
        
        <h3>EBL<br>UpConnect</h3>
        <div class="login-form">
            <form id="login-f" method="POST" action="login_process.php">
                <label for="username">Username:</label><br>
                <input type="text" id="username" name="username" required><br><br>

                <label for="password">Password:</label><br>
                <input type="password" id="password" name="password" required><br><br>

                <a href="index.php">Login as Student</a>
                <button type="submit">Login</button>
            </form>
        </div>

</body>
</html>
