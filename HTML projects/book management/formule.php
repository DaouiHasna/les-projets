<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Management</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #5f2c82, #49a09d);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
            padding: 40px;
            max-width: 800px;
            width: 100%;
            text-align: center;
        }
        .form-title {
            font-size: 48px;
            color: #fff;
            margin-bottom: 30px;
            font-family: 'Georgia', serif;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.4);
        }
        .data {
            width: 100%;
            height: 45px;
            margin-bottom: 25px;
            padding: 12px;
            font-size: 16px;
            border: 2px solid #fff;
            border-radius: 8px;
            background-color: #fff;
            color: #333;
            box-sizing: border-box;
            transition: all 0.3s ease;
        }
        .data:focus {
            border-color: #49a09d;
            outline: none;
            background-color: #f1f1f1;
        }
        .submit {
            background-color: #49a09d;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            width: 100%;
            transition: background-color 0.3s ease;
        }
        .submit:hover {
            background-color: #5f2c82;
        }
        .error {
            color: #f44336;
            margin-top: 20px;
            font-size: 16px;
        }
        .success {
            color: #4CAF50;
            margin-top: 20px;
            font-size: 16px;
        }
        table {
            margin-top: 30px;
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #fff;
        }
        th, td {
            padding: 18px;
            text-align: left;
            font-size: 18px;
        }
        th {
            background-color: #49a09d;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.1);
        }
        tr:nth-child(odd) {
            background-color: rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body>
<div class="container">
    <p class="form-title">Book Management</p>

    <?php
    include('connection.php');
    // Variables
    @$title = $_POST["title"];
    @$year = $_POST["year"];
    @$writer = $_POST["writer"];
    // Handle book insertion
    if (isset($_POST['add_book'])) {
        if (!empty($title) && !empty($year) && !empty($writer)) {
            $stmt = $conn->prepare("INSERT INTO books (title, year_of_publication, writer) VALUES (?, ?, ?)");
            $stmt->bind_param("sis", $title, $year, $writer);
            if ($stmt->execute()) {
                echo "<p class='success'>Book added successfully!</p>";
            } else {
                echo "<p class='error'>Error adding book: " . $stmt->error . "</p>";
            }
            $stmt->close();
        } else {
            echo "<p class='error'>All fields are required to add a book</p>";
        }
    }
    ?>
    <!-- Book Form -->
    <form method="post" action="">
        <input type="text" name="title" class="data" placeholder="Book Title" required /><br />
        <input type="number" name="year" class="data" placeholder="Year of Publication" required /><br />
        <input type="text" name="writer" class="data" placeholder="Writer" required /><br />
        <input type="submit" name="add_book" value="Add Book" class="submit" /><br />
    </form>
    <!-- Redirect to Display Books Page -->
    <form method="get" action="display_books.php">
        <br /><input type="submit" name="display_books" value="Display Books" class="submit" />
    </form>

    <?php
    $conn->close();
    ?>
</div>
</body>
</html>