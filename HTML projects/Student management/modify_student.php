<?php
// Include database connection file
include('connection.php');

// Fetch the student details if `id` is set
if (isset($_GET['id'])) {
    $student_id = $_GET['id'];

    // Fetch student data based on the provided ID
    try {
        $stmt = $conn->prepare("SELECT * FROM student WHERE id = :id");
        $stmt->bindParam(':id', $student_id);
        $stmt->execute();
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$student) {
            die("Student not found.");
        }
    } catch (PDOException $e) {
        $message = "<p class='error'>Error: " . $e->getMessage() . "</p>";
    }
}

// Handle form submission to update student details
if (isset($_POST['update_student'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $year_of_birth = $_POST['year_of_birth'];
    $etablissement_id = $_POST['etablissement_id'];

    try {
        // Update student in the database
        $stmt = $conn->prepare("UPDATE student SET name = :name, email = :email, year_of_birth = :year_of_birth, etablissement_id = :etablissement_id WHERE id = :id");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':year_of_birth', $year_of_birth);
        $stmt->bindParam(':etablissement_id', $etablissement_id);
        $stmt->bindParam(':id', $student_id);

        if ($stmt->execute()) {
            $message = "<p class='success'>Student updated successfully.</p>";
            header("Location: etablissement.php");
            exit();
        } else {
            $message = "<p class='error'>Error updating student.</p>";
        }
    } catch (PDOException $e) {
        $message = "<p class='error'>Error: " . $e->getMessage() . "</p>";
    }
}

// Fetch establishments for the dropdown
try {
    $stmt = $conn->query("SELECT * FROM etab_name");
    $etablissements = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $message = "<p class='error'>Error fetching establishments: " . $e->getMessage() . "</p>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify Student</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
            padding-top: 100px;
        }
        .container {
            max-width: 800px;
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
        input[type="text"], input[type="email"], input[type="number"], select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        input[type="submit"] {
            background-color: #6a1b9a;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #4a148c;
        }
        .error {
            color: red;
            font-size: 16px;
            margin: 10px 0;
        }
        .success {
            color: green;
            font-size: 16px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
<div class="container">
    <h2 class="form-title">Modify Student</h2>

    <!-- Display Message -->
    <?php if (isset($message)) { echo $message; } ?>

    <!-- Student Edit Form -->
    <form method="POST">
        <label for="name">Name</label><br>
        <input type="text" name="name" value="<?php echo $student['name']; ?>" required><br>

        <label for="email">Email</label><br>
        <input type="email" name="email" value="<?php echo $student['email']; ?>" required><br>

        <label for="year_of_birth">Year of Birth</label><br>
        <input type="number" name="year_of_birth" value="<?php echo $student['year_of_birth']; ?>" required><br>

        <label for="etablissement_id">Etablissement</label><br>
        <select name="etablissement_id" required>
            <?php
            foreach ($etablissements as $etab) {
                echo "<option value='" . $etab['id'] . "'" . ($etab['id'] == $student['etablissement_id'] ? " selected" : "") . ">" . $etab['name'] . "</option>";
            }
            ?>
        </select><br><br>

        <input type="submit" name="update_student" value="Update Student">
    </form>
</div>
</body>
</html>


