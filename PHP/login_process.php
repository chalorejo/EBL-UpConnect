<?php
    session_start();
    include 'connectDB.php';

    $error = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        function validate($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $username = validate($_POST['username']);
        $password = validate($_POST['password']);

        if (empty($username)) {
            header("Location: admin_login.php?error=Username is required");
            exit();
        } else if (empty($password)) {
            header("Location: admin_login.php?error=Password is required");
            exit();
        } else {
            $stmt = $conn->prepare("SELECT * FROM adminlogin WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();
                if ($password === $row['password']) {
                    $_SESSION['username'] = $row['username'];
                    header("Location: admin_view.php");
                    exit();
                } else {
                    header("Location: admin_login.php?error=Incorrect username or password");
                    exit();
                }
            } else {
                header("Location: admin_login.php?error=Incorrect username or password");
                exit();
            }
        }
    }
?>