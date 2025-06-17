<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - Student and Etablissement Management</title>
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
        .menu {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }
        .menu button {
            padding: 15px 25px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            background-color: #6a1b9a;
            color: white;
        }
        .menu button:hover {
            background-color: #4a148c;
        }
        h1 {
            color: #6a1b9a;
            text-align: center;
            font-size: 36px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Student and Etablissement Management</h1>
    <div class="menu">
        <button onclick="window.location.href='add_student.php'">Add Student</button>
        <button onclick="window.location.href='add_etablissement.php'">Add Etablissement</button>
        <button onclick="window.location.href='display_students.php'">Display Students</button>
        <button onclick="window.location.href='display_etablissements.php'">Display Etablissements</button>
    </div>
</div>
</body>
</html>
