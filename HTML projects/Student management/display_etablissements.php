<?php
// Include database connection file
include('connection.php');
include('main.php');
// Fetch all establishments
try {
    $stmt = $conn->prepare("SELECT * FROM etab_name");
    $stmt->execute();
    $etablissements = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Etablissement</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
            text-align: left;
        }
        th, td {
            padding: 10px;
        }
        th {
            background-color: #6a1b9a;
            color: white;
        }
        .back-btn {
            margin-top: 20px;
            display: block;
            text-align: center;
            background-color: #6a1b9a;
            color: white;
            padding: 10px;
            text-decoration: none;
            border-radius: 4px;
        }
        .back-btn:hover {
            background-color: #4a148c;
        }
    </style>
</head>
<body>
<div class="container">
    <p class="form-title">Display Etablissements</p>

    <?php
    if (isset($error_message)) {
        echo "<p class='error'>$error_message</p>";
    }

    if (!empty($etablissements)) {
        echo "<table>
                <thead>
                    <tr>
                        <th>Name</th>
                    </tr>
                </thead>
                <tbody>";
        foreach ($etablissements as $etablissement) {
            echo "<tr>
                    <td>" . $etablissement['name'] . "</td>
                  </tr>";
        }
        echo "</tbody>
            </table>";
    } else {
        echo "<p>No establishments found.</p>";
    }
    ?>
    
</div>
</body>
</html>

</html>

