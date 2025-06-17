<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Table Information</title>
    <style>
        body {
            font-family: sans-serif;
            background-color: azure;
            padding-left: 30px; /*espace between ecriture et edge*/
        }
        h3{
            color: #b8860b;
            margin-top: 20px;
        }

        li {
            background: beige;
            margin: 5px 0;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<?php
$tab = array("Karim", "Maroua", "Aya", "Mohammed", "Fatima");
echo "<h3>Tableau:</h3>";
echo "<pre>";
print_r($tab);
echo "</pre>";

$tab[] = "Salim";
echo "<h3>Après ajout de Salim :</h3>";
echo "<pre>";   /* using pre change the print from the original format to each in line */
print_r($tab);
echo "</pre>";

echo "<h3>Search for Karim and delete him :</h3>";
if (($key = array_search("Karim", $tab)) !== false) {
    unset($tab[$key]);
    $tab = array_values($tab);
}
echo "<pre>";
print_r($tab);
echo "</pre>";
  echo "<h3>Check existance de Mohammed dans le tableau</h3>";
if (($key = array_search("Mohammed", $tab)) !== false) {
    echo "<pre><font size='4px' color='#006400'> Mohammed est dans le tableau.</font></pre>";
} else {
    echo "<pre>Mohammed n'est PAS dans le tableau.</pre>";
}

echo "<h3>Modify Salim by Daniel :</h3>";
if (($key = array_search("Salim", $tab)) !== false) {
    $tab[$key] = "Daniel";
}
echo "<pre>";
print_r($tab);
echo "</pre>";

echo "<h3>Liste des étudiants :</h3>";
echo "<ul>";
foreach ($tab as $value) {
    echo "<li>$value</li>";
}
echo "</ul>";

sort($tab);
echo "<h3>Après tri par ordre alphabétique :</h3>";
echo "<pre>";
print_r($tab);
echo "</pre>";

echo "<h3>Table reversed :</h3>";
$tab2 = array_reverse($tab);
echo "<pre>";
print_r($tab2);
echo "</pre>";

echo "<h3>Nombre des étudiants :</h3>" . count($tab);

$tab1 = array("Karim" => 22, "Maroua" => 24);
echo "<h3>Tableau multidimensionnel (étudiants et âges) :</h3>";
echo "<pre>";
print_r($tab1);
echo "</pre>";
?>

</body>
</html>


