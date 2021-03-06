<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <title>Willkommen</title>


    <?php
    //lädt Seite nur, falls Login ok und User Type = User
    if (isset($_SESSION['name']) && isset($_SESSION['login']) && $_SESSION['login'] == "ok" && $_SESSION['user_type'] == "user") {

        //löscht Warenkorb nach Bestellung
        if (isset($_POST['bestellt'])) {
            unset($_SESSION['warenkorb']);
        }

        //fügt Elemente dem Warenkorb hinzu
        if (isset($_GET['e']) && $_GET['e'] == 1) {
            foreach ($_POST as $key => $value) {
                if ($value == "") {
                    $value = 0;
                }
                if (!isset($_SESSION['warenkorb'])) {
                    $_SESSION['warenkorb'] = $_POST;
                } elseif (array_key_exists($key, $_SESSION['warenkorb'])) {
                    @$_SESSION['warenkorb'][$key] += $value;
                } else {
                    $_SESSION['warenkorb'] += [$key => $value];
                }
            }
        }


        //Verbindung Datenbank
        $connection = mysqli_connect("", "root");

        //Datenbank auswählen
        mysqli_select_db($connection, "webshop");

        //Abfrage Text für alle Kategorien
        $sql_kat = "select name from kategorie";

        //SQL-Abfrage
        $result = mysqli_query($connection, $sql_kat);

        //Anzahl der Datensätze ermitteln
        $num = mysqli_num_rows($result);

        //Array aller Kategorien & Produkte & Ratings
        $kategorien = array();
        $products = array();
        $ratings = array();
        $j = 0;

        //ermittelte Kategorien werden in Kategorie Array geschrieben
        while ($dsatz = mysqli_fetch_assoc($result)) {
            $kategorien[$j] = $dsatz['name'];
            $j++;
        }

        //falls User eine Kategorie ausgewählt hat, hole die Nr diesser Kategorie
        if (isset($_POST['kategorie'])) {
            //Datenbank auswählen
            mysqli_select_db($connection, "webshop");

            //Abfrage Text für Kategorienummer
            $sql_kat = "select nummer from kategorie where name='" . $_POST['kategorie'] . "'";

            //SQL-Abfrage
            $result = mysqli_query($connection, $sql_kat);

            //Anzahl der Datensätze ermitteln
            $num = mysqli_num_rows($result);

            $kat_nr;
            $prod_nr = array();

            //falls  genau eine Kategorie gefunden, hole alle Produkte dieser Kategorie
            if ($num == 1) {
                $dsatz = mysqli_fetch_assoc($result);
                $kat_nr = $dsatz['nummer'];

                //Abfrage Text für alle Produkte
                $sql_prod = "select * from product where fk_kat='" . $kat_nr . "'";

                //SQL-Abfrage
                $result = mysqli_query($connection, $sql_prod);

                //Anzahl der Datensätze ermitteln
                $num = mysqli_num_rows($result);

                $o = 0;

                //schreibe alle gefundenen Produkte in einen Produkt-Array unf hol dir jeweils die Ratings
                while ($dsatz = mysqli_fetch_assoc($result)) {
                    $products[$o] = $dsatz;

                    //Abfrage Text für Ratings
                    $sql_rat = "select * from komments where fk_product='" . $products[$o]['Pr_Nummer'] . "'";

                    //SQL-Abfrage
                    $result_rat = mysqli_query($connection, $sql_rat);

                    //Anzahl der Datensätze ermitteln
                    $num_rat = mysqli_num_rows($result_rat);

                    $k = 0;

                    //schreibt in ratings alle Bewertungen der producte mit key Pr_Numemr und value rating
                    while ($dsatz_rat = mysqli_fetch_assoc($result_rat)) {
                        $ratings[$k] = [$products[$o]['Pr_Nummer'] => $dsatz_rat['rating']];
                        $k++;
                    }
                    $o++;
                }
            } else {
                echo "Fehler beim abfragen der Kategorienummer!";
            }
        }

        //schließt Datenbankverbindung
        mysqli_close($connection);

        //ermittelt den Durschnitt aller Bewertungen einer Produkts
        function bewertung($Pr_Nummer, $ratings)
        {
            $bewertung = 0.0;
            $anzahl = 0.0;

            foreach ($ratings as $key => $value) {
                foreach ($ratings[$key] as $rat => $value) {
                    if ($rat == $Pr_Nummer) {
                        $bewertung += $value;
                        $anzahl++;
                    }
                }
            }
            //checkt ob bereits Bewertungen vorhanden sind
            if ($anzahl == 0) {
                $ergebnis = "noch keine Bewertungen vorhanden";
            } else {
                $durch = $bewertung / $anzahl;
                $ergebnis = "" . $durch . " &#9733;";
            }

            //liefert Ergebnis zurück
            return $ergebnis;
        }


        //falls Admin, redirect auf Admin Seite
    } elseif (isset($_SESSION['name']) && isset($_SESSION['login']) && $_SESSION['login'] == "ok" && $_SESSION['user_type'] == "admin") {
        header("Location: admin.php");
        //Falls Login fehlgeschlagen redirect auf Login.php
    } else {
        header("Location: login.php");
    }
    ?>
</head>

<body>
    <div class="header">

        <!-- Begrüßung des Users mit Namen -->
        <h1>Hallo <?php if (!empty($_SESSION['name'])) {
                        echo $_SESSION['name'];
                    } ?>, willkommen auf dem Webshop!</h1>
    </div>

    <div class="topnav">
        <a href="logout.php">Logout</a>
        <a href="warenkorb.php">Warenkorb</a>
        <a href="ratings.php">Produkte bewerten</a>
    </div>

    <div class="row">
        <div class="column side">
            <h2>Kategorien</h2>
            <?php
            // anzeigen aller Kategorien, falls welche vorhanden/gefunden
            if (!empty($kategorien)) {
                echo "<ul class='categories'>";
                echo "<form action='home.php' method='post'>";
                for ($i = 0; $i < count($kategorien); $i++) {
                    echo "<input class='btn' type='submit' name='kategorie' value='" . $kategorien[$i] . "'> <br>";
                }
                echo "</form>";
                echo "</ul>";
            } else {
                echo "Leider keine Kategorien gefunden";
            }
            ?>
        </div>

        <div class="column middle">
            <?php
            // falls Producte gefunden und Kategorie ausgewählt, zeig diese an
            if (!empty($products)) {
                //kategorie als Überschrift
                echo "<h2><u>" . $_POST['kategorie'] . "</u></h2>";
                echo "<form action='home.php?e=1' method='post'>";
                echo "<table border='1'> <tr class='table_head'><td>Marke</td><td>Herkunft</td><td>Preis pro Kiste</td><td>Anzahl</td><td>Bewertungen</td></tr>";

                //erstellt Array mit aller Produkte
                $prods = array();

                //füllt Produkt Array und gibt diese in Tabelle aus
                foreach ($products as $item) {
                    foreach ($item as $key => $value) {
                        //füllt Produkte Array
                        $prods[$key] = $value;
                    }
                    echo "<tr><td> " . $prods['name'] . " </td><td> " . $prods['origin'] . "</td><td> " . $prods['price'] . " €</td><td> <input type ='text' name='" . $prods['Pr_Nummer'] . "' placeholder='0'></td>";
                    echo "<td>" . bewertung($prods['Pr_Nummer'], $ratings) . "</td></tr>";
                }


                echo "</table><br>";
                echo "<input class='kasse' style='border: none;' type='submit' value='In den Warenkorb'>";
                echo "</form>";
            } else {
                echo "<center><span class='material-icons'>
                        error_outline
                    </span>
                    <p>Wähle eine Kategorie!</p></center>";

                //Hinweis: Erfolgreich in den Warenkorb gelegt
                if (isset($_GET['e']) && $_GET['e'] == 1) {
                    echo "<center><p style='color: green;'>Deine gewählten Artikel wurden erfolgreich dem Warenkorb hinzugefügt!</p></center>";
                }
            }
            ?>
        </div>
    </div>


</body>
<footer>
    <p>Copyright &copy; 2020 Brero Webshops. All Rights Reserved</p>
</footer>

</html>