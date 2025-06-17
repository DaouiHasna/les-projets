<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Table Information</title>
    <style>
        body {
            font-family: sans-serif;
            background-color: azure;
            padding: 20px;
        }
        h3 {
            color: #b8860b;
        }
        table {
            border-collapse: collapse;
            width: 50%;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4a460;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>

<center><?php
$tab = array("Karim", "Maroua", "Aya", "Mohammed", "Fatima");

echo "<h3>Tableau initial :</h3>";
echo "<table>";
echo "<tr><th>Nom</th></tr>";
foreach ($tab as $value) {
    echo "<tr><td>$value</td></tr>";
}
echo "</table>";

$tab[] = "Salim";
echo "<h3>Après ajout de Salim :</h3>";
echo "<table>";
echo "<tr><th>Nom</th></tr>";
foreach ($tab as $value) {
    echo "<tr><td>$value</td></tr>";
}
echo "</table>";

echo "<h3>Recherche de Karim et suppression :</h3>";
if (($key = array_search("Karim", $tab)) !== false) {
    unset($tab[$key]);
    $tab = array_values($tab);
}
echo "<table>";
echo "<tr><th>Nom</th></tr>";
foreach ($tab as $value) {
    echo "<tr><td>$value</td></tr>";
}
echo "</table>";

echo "<h3>Vérification de l'existence de Mohammed :</h3>";
if (array_search("Mohammed", $tab) !== false) {
    echo "<p style='color: green;'>Mohammed est dans le tableau.</p>";
} else {
    echo "<p>Mohammed n'est PAS dans le tableau.</p>";
}

echo "<h3>Modification de Salim en Daniel :</h3>";
if (($key = array_search("Salim", $tab)) !== false) {
    $tab[$key] = "Daniel";
}
echo "<table>";
echo "<tr><th>Nom</th></tr>";
foreach ($tab as $value) {
    echo "<tr><td>$value</td></tr>";
}
echo "</table>";

sort($tab);
echo "<h3>Après tri alphabétique :</h3>";
echo "<table>";
echo "<tr><th>Nom</th></tr>";
foreach ($tab as $value) {
    echo "<tr><td>$value</td></tr>";
}
echo "</table>";

echo "<h3>Tableau inversé :</h3>";
$tab2 = array_reverse($tab);
echo "<table>";
echo "<tr><th>Nom</th></tr>";
foreach ($tab2 as $value) {
    echo "<tr><td>$value</td></tr>";
}
echo "</table>";

echo "<h3>Nombre d'étudiants :</h3>";
echo "<p>" . count($tab) . "</p>";

$tab1 = array("Karim" => 22, "Maroua" => 24, "Aya"=> 34, "Mohammed" =>13, "Fatima"=>20);
    echo "<h3>Tableau multidimensionnel (étudiants et âges) :</h3>";
    echo "<table>";
    echo "<tr><tr><th>Nom</th><th>Age</th></tr>";
    foreach ($tab1 as $key => $value) {
        echo "<tr><td>$value</td><td>$value</td></tr>";
    }
    echo "</table>";
?></center>

</body>
</html>

