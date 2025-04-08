<?php
ini_set('display_errors', 1);  // Show errors
error_reporting(E_ALL);         // Report all errors

include 'dbms.php';  // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo '<script>
            alert("Password does not match");
            window.location.href = "create acc.html";
            </script>';
            exit();
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if email already exists
        $check_email = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check_email->bind_param("s", $email);
        $check_email->execute();
        $result = $check_email->get_result();

        if ($result->num_rows > 0) {
           echo '<script>
            alert("Email is already taken");
            window.location.href = "create acc.html";
            </script>';
            exit();
        } else {
            // Insert user into database
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $hashed_password);

            if ($stmt->execute()) {
            echo '<script>
            alert("Account Successfully Created");
            window.location.href = "LOGIN.html";
            </script>';
            exit();
}
                
            $stmt->close();
        }
        $check_email->close();
    }

    $conn->close();
}
?>