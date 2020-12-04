<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <title>Warenkorb</title>

    <?php
    if (isset($_SESSION['name']) && isset($_SESSION['login']) && $_SESSION['login'] == "ok" && isset($_SESSION['warenkorb'])) {
        $products = array();
        $j = 0;

        //Verbindung Datenbank
        $connection = mysqli_connect("", "root");

        //Datenbank auswählen
        mysqli_select_db($connection, "webshop");

        foreach ($_SESSION['warenkorb'] as $prods => $values) {
            //Abfrage Text
            $sql = "select * from product where Pr_Nummer='" . $prods . "'";

            //SQL-Abfrage
            $result = mysqli_query($connection, $sql);

            while ($dsatz = mysqli_fetch_assoc($result)) {
                $products[$j] = $dsatz;
                $j++;
            }
        }


        //Verbindung trennen
        mysqli_close($connection);
    } elseif (isset($_SESSION['name']) && isset($_SESSION['login']) && $_SESSION['login'] == "ok" && !isset($_SESSION['warenkorb'])) {
        $leer = true;
    } else {
        header("Location: login.php");
    }
    ?>


</head>

<body>

    <div class="header">
        <h1>Warenkorb</h1>
    </div>

    <div class="topnav">
        <a href="logout.php">Logout</a>
        <a href="home.php">Zu den Produkten</a>
    </div>

    <div class="row">
        <center>
            <?php
            if (isset($leer)) {
                echo "<br><span class='material-icons'>
                error_outline
            </span><p style='color: green;'>Dein Warenkorb ist leer!<br> Füge neue Produkte hinzu, um diese zu bestellen.</p>";
            } else {
                echo "<h3>Diese Produkte befinden sich aktuell in deinem Warenkorb:</h3>";
                echo "<table border='1'> <tr class='table_head'><td>Produkt</td><td>Anzahl</td><td>Preis pro Kiste</td></tr>";

                $gesamt_preis = 0;
                $gesamt_anzahl = 0;

                foreach ($products as $item) {
                    foreach ($item as $key => $value) {
                    }
                    echo "<tr><td> " . $item['Name'] . " </td><td> " . $_SESSION['warenkorb'][$item['Pr_Nummer']] . " Kisten</td><td>" . $item['Price'] . " € </td></tr>";

                    $gesamt_anzahl += $_SESSION['warenkorb'][$item['Pr_Nummer']];
                    $gesamt_preis += ($item['Price'] * $_SESSION['warenkorb'][$item['Pr_Nummer']]);
                }
                echo "<tr class='table_bottom'><td>Gesamt</td><td>$gesamt_anzahl Kisten</td><td>$gesamt_preis €</td></tr>";
                echo "</table><br>";
                echo "<a class='kasse' href='kasse.php'>Zur Kasse</a>";
            }
            ?>
        </center>
    </div>



</body>
<footer>
    <p>Copyright &copy; 2020 Brerik Webshops. All Rights Reserved</p>
</footer>


</html>