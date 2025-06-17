<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Books</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #5f2c82, #49a09d);
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 1000px;
            width: 100%;
            text-align: center;
        }

        .form-title {
            font-size: 48px;
            color: #333;
            margin-bottom: 30px;
            font-family: 'Georgia', serif;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
        }

        /* Table styles */
        table {
            border-radius: 20px;
            margin-top: 30px;
            width: 100%;
            border-collapse: collapse;
            background: #fff; /* White background for the table */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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

        /* Color for title, year, and writer cells */
        td {
            color: #333;
            background-color: white;
        }


        /* Back to login button */
        .back-link {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 25px;
            background-color: #49a09d; /* Same color as the header */
            color: white;
            text-decoration: none;
            font-size: 18px;
            border-radius: 8px;
            transition: background-color 0.3s;
        }

        .back-link:hover {
            background-color: #5f2c82;
        }
    </style>
</head>
<body>
<div class="container">
    <p class="form-title">Books List</p>

    <?php
    include('connection.php');



    //display the books
    $sql = "SELECT title, year_of_publication, writer FROM books";
    $result = $conn->query($sql); //execute an SQL query on a MySQL database and store the result.

    if ($result->num_rows > 0) { //if rows exist
        echo "<table>";
        echo "<tr><th>Title</th><th>Year</th><th>Writer</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row['title'] . "</td><td>" . $row['year_of_publication'] . "</td><td>" . $row['writer'] . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No books found in the database</p>";
    }

    $conn->close();
    ?>

    <!-- Back to form/login button -->
    <a href="formule.php" class="back-link">Back to Form</a>
</div>
</body>
</html>


