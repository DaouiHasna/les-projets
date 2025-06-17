<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Table Information</title>
    <style>
        body {
            font-family: Arial,sans-serif;
            padding: 20px;
            color: #333;
        }
        table {
            border-collapse: collapse;
            width: 70%;
            margin: 20px auto;
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            border-radius: 10px;
        }
        th {
            background-color: #5a189a;
            color: white;
            padding: 15px;
            font-size: 18px;
            text-transform: uppercase;
            border: none;
        }
        td {
            padding: 12px;
            font-size: 16px;
            text-align: center;
            border: 1px solid #ddd;
        }
        tr:hover td{
            background-color: burlywood;
        }
        p {
            font-size: 18px;
            text-align: center;
            margin-top: 10px;
            color: #5a189a;
        }

        .index-name th   {
            background-color: #ba55d3;
            color: white;
            text-align: center;
            font-size: 20px;
            padding: 15px;
        }
        .index-name {
            background-color: #f0e6f6;
            color: #333;
            font-size: 16px;
        }
        .student-count-table {
            width: 25%;
            margin: 10px auto;
            border: 1px solid #ddd;
        }
        .student-count-table td {
            background-color: #f0e6f6;
            font-size: 16px;
        }
    </style>
</head>
<body>

<center>
    <?php
    $tab = array("Karim", "Maroua", "Aya", "Mohammed", "Fatima");
    echo "<caption><b><font color='#ba55d3' size='20pt'>Table Manipulation en PHP</font></b></caption><br><br><br>";

    // Display Initial Table with Heading
    echo "<table>";
    echo "<thead><tr class='title-row'><th colspan='2'>Tableau Initial :</th></tr></thead>";
    echo "<thead><tr class='index-name'><th>Indice</th><th>Nom</th></tr></thead>";
    echo "<tbody>";
    foreach ($tab as $index => $value) {
        echo "<tr><td class='index-name'>" . ($index + 1) . "</td><td class='index-name'>$value</td></tr>";
    }
    echo "</tbody></table>";

    $tab[] = "Salim";
    // Display Table after adding "Salim"
    echo "<table>";
    echo "<thead><tr class='title-row'><th colspan='2'>Apres ajout de Salim :</th></tr></thead>";
    echo "<thead><tr class='index-name'><th>Indice</th><th>Nom</th></tr></thead>";
    echo "<tbody>";
    foreach ($tab as $index => $value) {
        echo "<tr><td class='index-name'>" . ($index + 1) . "</td><td class='index-name'>$value</td></tr>";
    }
    echo "</tbody></table>";

    // Remove "Karim" and Display
    echo "<table>";
    echo "<thead><tr class='title-row'><th colspan='2'>Recherche de Karim et suppression :</th></tr></thead>";
    echo "<thead><tr class='index-name'><th>Indice</th><th>Nom</th></tr></thead>";
    echo "<tbody>";
    if (($key = array_search("Karim", $tab)) !== false) {
        unset($tab[$key]);
        $tab = array_values($tab);
    }
    foreach ($tab as $index => $value) {
        echo "<tr><td class='index-name'>" . ($index + 1) . "</td><td class='index-name'>$value</td></tr>";
    }
    echo "</tbody></table>";

    // Check for "Mohammed"
    echo "<table>";
    echo "<thead><tr class='title-row'><th colspan='2'>Vérification de l'existence de Mohammed :</th></tr></thead>";
    echo "<tbody>";
    if (array_search("Mohammed", $tab) !== false) {
        echo "<tr><td colspan='2'>Mohammed est dans le tableau.</td></tr>";
    } else {
        echo "<tr><td colspan='2'>Mohammed n'est PAS dans le tableau.</td></tr>";
    }
    echo "</tbody></table>";

    // Modify "Salim" to "Daniel"
    echo "<table>";
    echo "<thead><tr class='title-row'><th colspan='2'>Modification de Salim en Daniel :</th></tr></thead>";
    echo "<thead><tr class='index-name'><th>Indice</th><th>Nom</th></tr></thead>";
    echo "<tbody>";
    if (($key = array_search("Salim", $tab)) !== false) {
        $tab[$key] = "Daniel";
    }
    foreach ($tab as $index => $value) {
        echo "<tr><td class='index-name'>" . ($index + 1) . "</td><td class='index-name'>$value</td></tr>";
    }
    echo "</tbody></table>";

    // Sort alphabetically and Display
    sort($tab);
    echo "<table>";
    echo "<thead><tr class='title-row'><th colspan='2'>Après tri alphabétique :</th></tr></thead>";
    echo "<thead><tr class='index-name'><th>Indice</th><th>Nom</th></tr></thead>";
    echo "<tbody>";
    foreach ($tab as $index => $value) {
        echo "<tr><td class='index-name'>" . ($index + 1) . "</td><td class='index-name'>$value</td></tr>";
    }
    echo "</tbody></table>";

    // Reverse the table and Display
    echo "<table>";
    echo "<thead><tr class='title-row'><th colspan='2'>Tableau inversé :</th></tr></thead>";
    echo "<thead class='index-name'><tr><th>Indice</th><th>Nom</th></tr></thead>";
    echo "<tbody>";
    $tab2 = array_reverse($tab);
    foreach ($tab2 as $index => $value) {
        echo "<tr><td class='index-name'>" . ($index + 1) . "</td><td class='index-name'>$value</td></tr>";
    }
    echo "</tbody></table>";

    // Display Count of Students in a small table
    echo "<table class='student-count-table'>";
    echo "<thead><tr><th colspan='2'>Nombre d'étudiants :</th></tr></thead>";
    echo "<tbody><tr><td>Nombre total</td><td>" . count($tab) . "</td></tr></tbody>";
    echo "</table>";

    // Display Multidimensional Array (Names and Ages)
    $tab1 = array("Karim" => 22, "Maroua" => 24, "Aya"=> 34, "Mohammed" =>13, "Fatima"=>20);
    echo "<table>";
    echo "<thead><tr class='title-row'><th colspan='3'>Tableau multidimensionnel (étudiants et âges) :</th></tr></thead>";
    echo "<thead><tr class='index-name'><th>Indice</th><th>Nom</th><th>Age</th></tr></thead>";
    echo "<tbody>";
    $i = 1;
    foreach ($tab1 as $key => $value) {
        echo "<tr><td class='index-name'>" . $i++ . "</td><td class='index-name'>$key</td><td class='index-name'>$value</td></tr>";
    }
    echo "</tbody></table>";
    ?>
</center>

</body>
</html>