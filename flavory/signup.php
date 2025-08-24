<?php session_start(); ?>
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
    </style>
</head>
<body>
    <div class="header">
        <img src=".\image\logo.png" alt="Me Tube Logo">
        <div class="buttons">
            <button onclick="location.href='login.php';" >login</button>
            <button onclick="location.reload();" class="active">signup</button>
        </div>
    </div>


    <div class="signup-form">
    <h2>Sign Up</h2>
    <?php
    if (isset($_SESSION['error'])) {
        echo '<p style="color:red;">' . $_SESSION['error'] . '</p>';
        unset($_SESSION['error']); // Remove error after displaying it
    }
?>
        <br>
    <form method="POST" action="signup_process.php">
        <div class="form-group">
            <label for="username" >Username</label>
            <input type="text" id="username" placeholder="Enter your username" name="username">
        </div>
        <div class="form-group">
            <label for="email" >Email</label>
            <input type="email" id="email" placeholder="Enter your email" name="email">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <div class="password-container">
                <input type="password" id="password" placeholder="Enter your password" name="password">
                <span class="toggle-password" onclick="togglePasswordVisibility('password')">👁</span>
            </div>
        </div>
        <div class="form-group">
            <label for="confirm-password">Confirm Password</label>
            <div class="password-container">
                <input type="password" id="confirm-password" placeholder="Confirm your password" name="confirm_password">
                <span class="toggle-password" onclick="togglePasswordVisibility('confirm-password')">👁</span>
            </div>
        </div>
        <button type="submit">Sign Up</button>
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
