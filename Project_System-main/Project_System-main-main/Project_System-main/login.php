<?php
session_start();
include 'dbms.php'; // Database connection


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Check if user exists
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $user['password'])) {
            
            echo '<script>
            window.location.href = "./admin/dashboard.html";
            </script>';
            exit();

        } else {
            $message = '<div class="alert alert-danger">Invalid email or password.</div>';
        }
    } else {
        $message = '<div class="alert alert-danger">Invalid email or password.</div>';
    }

    $stmt->close();
    $conn->close();
}   
?>