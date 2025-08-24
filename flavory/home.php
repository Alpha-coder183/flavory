<?php
session_start();
require 'db_connection.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $_SESSION['error'] = 'Please fill in both fields.';
        header('Location: login.php');
        exit();
    }

    // Query to check if the username exists
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verify the hashed password
        if (password_verify($password, $user['password'])) {
            //main code
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flavory</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .header {
            background-color: #e3f2fd; /* Very light blue */
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header img {
            height: 50px; /* Adjust logo size */
        }

        .header .buttons {
            display: flex;
            gap: 15px;
        }

        .header .buttons button {
            background-color: white;
            color: #007BFF; /* Blue for button text */
            border: 1px solid #007BFF;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .header .buttons button:hover {
            background-color: #007BFF;
            color: white;
        }
        
        .header .buttons .active {
            background-color: #0056b3; /* Darker blue for active button */
            color: white;
            border: 1px solid #0056b3;
        }
        .signup-form {
        width: 100%;
        max-width: 400px;
        margin: 20px auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        background-color: #f9f9f9;
        box-sizing: border-box; /* Ensures padding doesn't increase the total width */
    }

    .signup-form h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
    }

    .signup-form .form-group {
        margin-bottom: 15px;
        width: 100%;
        box-sizing: border-box; /* Ensures child elements respect container width */
    }

    .signup-form label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        color: #555;
    }

    .signup-form input {
        width: 100%; /* Ensures inputs take up the full width of the parent container */
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
        box-sizing: border-box; /* Ensures padding doesn't overflow */
    }

    .signup-form .password-container {
        position: relative;
        width: 100%; /* Ensures password container respects the container's width */
    }

    .signup-form .password-container input {
        width: 100%; /* Ensures the input takes up the full width */
        padding-right: 40px; /* Space for the eye icon */
        box-sizing: border-box;
    }

    .signup-form .toggle-password {
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
        cursor: pointer;
        color: #888;
        font-size: 18px;
    }
    .signup-form .toggle-password img {
        width: 10%;
        height: 10%;
        display: block;
    }
    .signup-form .toggle-password:hover {
        color: #555;
    }

    .signup-form button {
        width: 100%;
        padding: 10px;
        background-color: #007BFF;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        box-sizing: border-box;
    }

    .signup-form button:hover {
        background-color: #0056b3;
    }

    a {
        text-decoration: none;
        font-size: 14px;
        color: #007BFF; /* Blue color for the link */
        transition: color 0.3s ease;
    }

    a:hover {
        color: #0056b3; /* Darker blue on hover */
        text-decoration: underline; /* Add underline on hover */
    }

    a:focus {
        outline: none;
        color: #0056b3;
        text-decoration: underline;
    }
    </style>
</head>
<body>
    <div class="header">
        <img src=".\image\logo.png" alt="Me Tube Logo">
    </div>


    <div class="signup-form">
    <h2>Find Recipe</h2>
    <?php 
    if (isset($_SESSION['error'])) {
        echo "<p style='color: red;'>" . $_SESSION['error'] . "</p>";
        unset($_SESSION['error']); // Clear the error after displaying it
    }
?>
    <form method="POST" action="recipe.php">
        <div class="form-group">
            <input type='hidden' name='username' value=<?php echo $username;?>>
            <input type='hidden' name='password' value=<?php echo $password;?>>
            <label for="username">Prompt</label>
            <input type="text" id="username" name="prompt" placeholder="Enter your prompt">
        </div>
        <button type="submit">Find</button>
    </form>
    
</div>
    
<script>
    function togglePasswordVisibility(inputId) {
        const passwordField = document.getElementById(inputId);
        if (passwordField.type === "password") {
            passwordField.type = "text";
        } else {
            passwordField.type = "password";
        }
    }
</script>

</body>
</html>

<?php
            //main code
        } else {
            $_SESSION['error'] = 'Incorrect password.';
            header('Location: login.php');
            exit();
        }
    } else {
        $_SESSION['error'] = 'Username not found.';
        header('Location: login.php');
        exit();
    }
}
else{
    header('Location: error.php');
}
?>
