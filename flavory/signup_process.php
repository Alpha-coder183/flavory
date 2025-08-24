<?php
session_start();
include 'db_connection.php'; // Ensure this file contains your DB connection setup

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check for required fields
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['error'] = 'All fields are required';
        header('Location: signup.php');
        exit;
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        $_SESSION['error'] = 'Passwords do not match';
        header('Location: signup.php');
        exit;
    }

    

    // Check if the username already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = 'Username taken';
        header('Location: signup.php');
        exit;
    }

    // Check if the email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = 'Email used in another account';
        header('Location: signup.php');
        exit;
    }

    // Insert new user into the database
    $hashed_password = password_hash($password, PASSWORD_BCRYPT); // Hashing the password for security
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        // Auto post to home.php
        echo "
        <form id='autopost' action='home.php' method='POST'>
            <input type='hidden' name='username' value='$username'>
            <input type='hidden' name='password' value='$password'>
        </form>
        <script>document.getElementById('autopost').submit();</script>";
    } else {
        $_SESSION['error'] = 'Error signing up. Please try again.';
        header('Location: signup.php');
    }

    $stmt->close();
    $conn->close();
} else {
    $_SESSION['error'] = 'Invalid request';
    header('Location: signup.php');
}
?>
