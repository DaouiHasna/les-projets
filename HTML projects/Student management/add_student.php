<?php
// Include database connection file
include('connection.php');
include('main.php');
// Handle form submission for adding a student
if (isset($_POST['add_student'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $year_of_birth = $_POST['year_of_birth'];
    $etablissement_id = $_POST['etablissement_id'];
    $message = '';

    if (!empty($name) && !empty($email) && !empty($year_of_birth) && !empty($etablissement_id)) {
        try {
            // Prepare SQL query to insert student
            $stmt = $conn->prepare("INSERT INTO student (name, email, year_of_birth, etablissement_id) 
                                    VALUES (:name, :email, :year_of_birth, :etablissement_id)");

            // Bind parameters
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':year_of_birth', $year_of_birth);
            $stmt->bindParam(':etablissement_id', $etablissement_id);

            // Execute query
            if ($stmt->execute()) {
                $message = "<p class='success'>Student added successfully!</p>";
            } else {
                $message = "<p class='error'>Error adding student.</p>";
            }
        } catch (PDOException $e) {
            $message = "<p class='error'>Error: " . $e->getMessage() . "</p>";
        }
    } else {
        $message = "<p class='error'>Please fill all fields.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
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
    <p class="form-title">Add Student</p>

    <!-- Student Form -->
    <form method="post" action="">
        <input type="text" name="name" class="data" placeholder="Enter Student Name" required /><br />
        <input type="email" name="email" class="data" placeholder="Enter Student Email" required /><br />
        <input type="number" name="year_of_birth" class="data" placeholder="Enter Year of Birth" required /><br />

        <!-- Etablissement Dropdown -->
        <label for="etablissement">Select Etablissement:</label>
        <select name="etablissement_id" class="data" required>
            <option value="">Select an Etablissement</option>
            <?php
            try {
                // Fetch all etablissements from the 'etab_name' table
                $query = "SELECT id, name FROM etab_name";
                $stmt = $conn->query($query);
                while ($etablissement = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='" . $etablissement['id'] . "'>" . $etablissement['name'] . "</option>";
                }
            } catch (PDOException $e) {
                echo "<option value=''>No etablissements found</option>";
            }
            ?>
        </select><br />

        <input type="submit" name="add_student" value="Add Student" class="submit" />
    </form>

    <!-- Display Message -->
    <?php if (isset($message)) { echo $message; } ?>

</div>
</body>
</html>

