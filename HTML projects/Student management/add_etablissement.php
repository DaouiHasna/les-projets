<?php
// Include database connection file
include('connection.php');
include('main.php');
// Handle form submission for adding an establishment
if (isset($_POST['add_etablissement'])) {
    $etablissement_name = $_POST['etablissement_name'];
    $message = '';

    if (!empty($etablissement_name)) {
        try {
            // Prepare SQL query to insert establishment
            $stmt = $conn->prepare("INSERT INTO etab_name (name) VALUES (:name)");
            $stmt->bindParam(':name', $etablissement_name);

            // Execute query
            if ($stmt->execute()) {
                $message = "<p class='success'>Etablissement added successfully!</p>";
            } else {
                $message = "<p class='error'>Error adding Etablissement.</p>";
            }
        } catch (PDOException $e) {
            $message = "<p class='error'>Error: " . $e->getMessage() . "</p>";
        }
    } else {
        $message = "<p class='error'>Please provide an Etablissement name</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Etablissement</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
            padding-top: 100px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
        }
        .form-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
            color: #6a1b9a;
        }
        .data {
            margin: 10px 0;
            padding: 10px;
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .submit {
            margin: 10px 0;
            padding: 10px;
            width: 100%;
            background-color: #6a1b9a;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        .submit:hover {
            background-color: #4a148c;
        }
        .success {
            color: green;
            font-size: 16px;
            margin: 10px 0;
        }
        .error {
            color: red;
            font-size: 16px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
<div class="container">
    <p class="form-title">Add Etablissement</p>

    <!-- Etablissement Form -->
    <form method="post" action="">
        <input type="text" name="etablissement_name" class="data" placeholder="Enter Etablissement Name" required /><br />
        <input type="submit" name="add_etablissement" value="Add Etablissement" class="submit" />
    </form>

    <!-- Display Message -->
    <?php if (isset($message)) { echo $message; } ?>

</div>
</body>
</html>
