<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #eef2f3;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #ffffff;
            max-width: 700px;
            width: 100%;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
            display: flex;
            gap: 20px;
            align-items: center;
        }
        .login-title {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        .data {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: 1px solid #ddd;
            font-size: 16px;
            box-sizing: border-box;
        }
        .submit {
            width: 100%;
            padding: 12px;
            background-color: purple;
            color: white;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .submit:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            font-size: 14px;
            margin-top: 10px;
            text-align: left;
        }
        .login-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .next-image {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .next-image img {
            max-width: 100%;
            max-height: 300px;
            border-radius: 8px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="next-image">
        <img src="Screenshot_2024-12-20_201813-removebg-preview.png" alt="Next">
    </div>
    <div class="login-container">
        <p class="login-title">Log in</p>
        <form method="post" action="">
            <input type="text" name="username" class="data" placeholder="Username" required />
            <input type="password" name="password" class="data" placeholder="Password" required />
            <input type="submit" name="submit" value="Submit" class="submit" />
        </form>
        <?php
        session_start();
        // Variables
        $correctUsername = "hasna";
        $correctPassword = "1234";
        @$username = $_POST["username"];
        @$password = $_POST["password"];
        if (isset($_POST['submit'])){
            if ($username === $correctUsername && $password === $correctPassword) {
                $_SESSION["autoriser"] = "oui";
                header("Location:etablissement.php");
                exit();
            } else {
                $errorMessage = empty($username) ? "Username is required" : (empty($password) ? "Password is required" : "Wrong username or password");
                echo "<p class='error'>$errorMessage</p>";
            }
        }
        ?>
    </div>
</div>
</body>
</html>
