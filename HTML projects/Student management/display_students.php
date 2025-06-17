
<?php
// Include database connection file
include('connection.php');
include('main.php');
// Handle student deletion
if (isset($_GET['delete_id'])) {
    $student_id = $_GET['delete_id'];

    try {
        // Prepare SQL query to delete student
        $stmt = $conn->prepare("DELETE FROM student WHERE id = :id");
        $stmt->bindParam(':id', $student_id);

        // Execute query
        if ($stmt->execute()) {
            header("Location: display_students.php"); // Redirect to refresh the page
            exit();
        } else {
            $message = "<p class='error'>Error deleting student.</p>";
        }
    } catch (PDOException $e) {
        $message = "<p class='error'>Error: " . $e->getMessage() . "</p>";
    }
}

// Fetch students from the database
try {
    $query = "SELECT student.id, student.name, student.email, student.year_of_birth, etab_name.name as etablissement_name 
              FROM student 
              JOIN etab_name ON student.etablissement_id = etab_name.id";
    $stmt = $conn->query($query);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $message = "<p class='error'>Error fetching students: " . $e->getMessage() . "</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
            padding-top: 100px;
        }
        .container {
            max-width: 900px;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .button {
            padding: 5px 10px;
            margin: 5px;
            border: none;
            cursor: pointer;
        }
        .modify {
            background-color: #6a1b9a;
            color: white;
        }
        .modify:hover {
            background-color: #4a148c;
        }
        .delete {
            background-color: #ff6347;
            color: white;
        }
        .delete:hover {
            background-color: #d32f2f;
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
    <p class="form-title">Students List</p>

    <!-- Display Message -->
    <?php if (isset($message)) { echo $message; } ?>

    <!-- Students Table -->
    <table>
        <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Year of Birth</th>
            <th>Etablissement</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if (!empty($students)) {
            foreach ($students as $student) {
                echo "<tr>";
                echo "<td>" . $student['name'] . "</td>";
                echo "<td>" . $student['email'] . "</td>";
                echo "<td>" . $student['year_of_birth'] . "</td>";
                echo "<td>" . $student['etablissement_name'] . "</td>";
                echo "<td>
                            <a href='modify_student.php?id=" . $student['id'] . "' class='button modify'>Modify</a>
                            <a href='display_students.php?delete_id=" . $student['id'] . "' class='button delete' onclick='return confirm(\"Are you sure you want to delete this student?\")'>Delete</a>
                          </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No students found</td></tr>";
        }
        ?>
        </tbody>
    </table>

</div>
</body>
</html>


