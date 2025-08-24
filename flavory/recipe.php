<?php
session_start();
require 'db_connection.php'; // Include your database connection file
ini_set('memory_limit', '1G');
function generateText($query) {
    $url = "https://api.edenai.run/v2/text/generation";
    $headers = [
        "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoiODliMDIyYjctZjljMC00YWUyLTkwMGUtYzBmMGQwZGI1ZmZlIiwidHlwZSI6ImFwaV90b2tlbiJ9.uQMl2tLml-RIXZs-x9_4gAPlSnm4fIuC3qDMQKuRlkw",
        "Content-Type: application/json"
    ];

    $payload = json_encode([
        "providers" => "openai",
        "text" => $query,
        "temperature" => 0.2,
        "max_tokens" => 250,
        "fallback_providers" => "cohere"
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    curl_close($ch);

    if ($response !== false) {
        $result = json_decode($response, true);
        return $result['openai']['generated_text'] ?? 'No text generated from OpenAI';
    } else {
        return "Sorry, I didn't get you";
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $prompt = $_POST['prompt'];

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
    button {
        width: 20%;
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
    button:hover {
        background-color: #0056b3;
    }
    input {
        width: 100%; /* Ensures inputs take up the full width of the parent container */
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
        box-sizing: border-box; /* Ensures padding doesn't overflow */
    }
    </style>
</head>
<body>
    <div class="header">
        <img src=".\image\logo.png" alt="Me Tube Logo">
    </div>

            <?php
            
            $api_url = "https://api.openai.com/v1/images/generations";
            $api_key = "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";
            $data = [
                "prompt" => $prompt,
                "n" => 1,
                "size" => "1024x1024"
            ];
        
            // Initialize cURL session
            $ch = curl_init($api_url);
        
            // Set cURL options
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer $api_key",
                "Content-Type: application/json"
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        
            // Execute the request and handle errors
            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
        
            if ($http_code == 200) {
                $response_data = json_decode($response, true);
                $image_url = $response_data['data'][0]['url'];
            } else {
                $error_message = "Failed to generate the image. Please try again.";
                $image_url = null;
            }
            ?>
            <center><img src="<?php echo $image_url; ?>" alt="Generated Image" style="width: 200px; height: auto;"><br></center>
            <?php  ?>
            <h3>Ingredients</h3>
            <?php
            $query = $prompt. " give the ingredients to make this food in a ol li html manner";
            $result = generateText($query);
            $to_remove = "Unfortunately, I am an AI and do not have the ability to provide recipes";
            $result = str_replace($to_remove, "", $result);
            // Trim the string to remove any extra spaces or periods
            $result = trim($result);
            echo $result;
            ?>
            <br>
            <h3>Process</h3>
            <?php
            $query = $prompt. " give the recipie of this food no need to mention ingredients just mention the process in a ol li html manner";
            $result = generateText($query);
            $to_remove = "Unfortunately, I am an AI and do not have the ability to provide recipes";
            $result = str_replace($to_remove, "", $result);
            // Trim the string to remove any extra spaces or periods
            $result = trim($result);
            echo $result;
?>
<h4>Find another</h4>
<form method="POST" action="recipe.php">
    <input type='hidden' name='username' value=<?php echo $username;?>>
    <input type='hidden' name='password' value=<?php echo $password;?>>
    <input type="text" id="prompt" name="prompt" placeholder="Enter your prompt" style="width: 80%; margin: 0 auto; display: block;">
    <br>
    <center><button type="submit">Find</button></center>
<?php





?>

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
