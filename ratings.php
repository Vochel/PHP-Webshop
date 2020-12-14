<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="styles.css">
    <title>Bewertungen</title>

    <?php
    //lädt Seite nur, falls Login ok und User Type = User
    if (
        isset($_SESSION['name']) && isset($_SESSION['login']) && $_SESSION['login'] == "ok" &&
        $_SESSION['user_type'] = "user"
    ) {

        $task;
        global $bier;

        //setz Get-variablen für spätere Verwendung
        if (isset($_POST['bewerten']) || isset($_GET['t']) && $_GET['t'] == "1") {
            $task = ['bewerten' => 'ein Produkt bewerten'];
        } elseif (isset($_POST['ansehen']) || isset($_GET['t']) && $_GET['t'] == "2") {
            $task = ['ansehen' => 'Bewertungen ansehen'];
        }

        //Verbindung Datenbank
        $connection = mysqli_connect("", "root");

        //Datenbank auswählen
        mysqli_select_db($connection, "webshop");

        //Abfrage Text aller Produkte
        $sql = "select * from product";

        //SQL-Abfrage
        $result = mysqli_query($connection, $sql);

        //Anzahl der Datensätze ermitteln
        $num = mysqli_num_rows($result);

        //Array aller Produkte
        $products = array();
        $j = 0;

        while ($dsatz = mysqli_fetch_assoc($result)) {
            //schreibt alle gefunden Produkte in einen Array
            $products[$j] = $dsatz;
            $j++;
        }


        //Funktion erzeugt eine Auswahl aus alle Produkten
        function produkte($products, $task)
        {
            echo "<form action='";
            //ändert Formular-Get-Variablen je nach User auswahl
            if (isset($task['bewerten'])) {
                echo "ratings.php?t=1";
            } elseif (isset($task['ansehen'])) {
                echo "ratings.php?t=2";
            }
            echo "' method='post'><table border='1'> <tr class='table_head'><td>Auswahl</td><td>Marke</td></tr>";

            $prods = array();

            //holt Produkte aus Produkt-Array für die anzeige in einer Tabelle
            foreach ($products as $item) {
                foreach ($item as $key => $value) {
                    $prods[$key] = $value;
                }
                echo "<tr><td><input type='radio' name='bier' value='" . $prods['Pr_Nummer'] . "' required></td><td> " . $prods['name'] . "</td></tr>";
            }
            echo "</table><br>";
            echo "<input class='kasse' style='border: none;' type='submit' value='";
            //benennt Absendebtn je nach User Auswahl
            if (isset($task['bewerten'])) {
                echo "bewerten";
            } elseif (isset($task['ansehen'])) {
                echo "ansehen";
            }
            echo "'></form>";
        }

        //Funktion zeigt alle Bewertungen eine gewählten Produkts an
        function ansehen($bier_nr)
        {

            //Verbindung Datenbank
            $conn = mysqli_connect("", "root");

            //Datenbank auswählen
            mysqli_select_db($conn, "webshop");

            //Abfrage Text --> alle Bewertungen des gewählten Produkts 
            $sql_comments = "select * from komments where fk_product='" . $bier_nr . "'";

            //SQL-Abfrage
            $result = mysqli_query($conn, $sql_comments);

            $n = 0;
            while ($dsatz = mysqli_fetch_assoc($result)) {
                //schreibt alle gefundenen Bewertungen in eine Session
                $_SESSION['comments'][$n] = $dsatz;
                $n++;
            }

            //Abfrage Text --> alle Daten des gewählten Produkts 
            $sql_prod = "select * from product where Pr_Nummer='" . $bier_nr . "'";

            //SQL-Abfrage
            $result = mysqli_query($conn, $sql_prod);

            while ($dsatz = mysqli_fetch_assoc($result)) {
                //Titel anzeige
                $_SESSION['bier'] = $dsatz['name'];
                echo "<h2>Hier siehst du alle bewertungen für " . $dsatz['name'] . ": </h2>";
            }

            //falls noch keine Bewertungen vorhanden
            if (!isset($_SESSION['comments'])) {
                echo "Leider sind noch keine Bewertungen für dieses Produkt vorhanden. <br>";
            } else {
                //Ausgabe aller gefundenen Bewertungen
                echo "<br><table border='1'> <tr class='table_head'><td>Bewertung</td><td>Kommentar</td></tr>";
                foreach ($_SESSION['comments'] as $com) {
                    echo "<tr><td> " . $com['rating'] . " &#9733;</td><td> " . $com['komment'] . "</td>";
                }
                echo "</table><br>";
            }

            //Verbindung schließen
            mysqli_close($conn);

            unset($_SESSION['comments']);
        }

        //Funktion zum Bewerten eines Produkts
        function bewerten($bier_nr)
        {
            //Verbindung Datenbank
            $conn = mysqli_connect("", "root");

            //Datenbank auswählen
            mysqli_select_db($conn, "webshop");

            //Abfrage Text --> alle Daten des gewählten Produkts
            $sql = "select * from product where Pr_Nummer='" . $bier_nr . "'";

            //SQL-Abfrage
            $result = mysqli_query($conn, $sql);

            while ($dsatz = mysqli_fetch_assoc($result)) {
                //Titel ausgabe + Daten speichern
                $_SESSION['bier'] = $dsatz;
                echo "<h2>Füge hier dein Kommentar für " . $dsatz['name'] . " hinzu:";
            }

            //Formular zum bewerten des Produkts
            echo "<form action='ratings.php?e=1' method='post'>";
            echo "<br><input type='text' name='sterne' required placeholder='Wie viele Sterne? (1-5)'><br>";
            echo "<input type='text' name='comment' required size='60' placeholder='Dein Kommertar'><br><br>";
            echo "<input class='kasse' style='border: none;' type='submit' value='Absenden'>";
            echo "</form>";

            //Verbindung schließen
            mysqli_close($conn);
        }

        //Verbindung schließen
        mysqli_close($connection);
    } else {
        header("Location: login.php");
    }
    ?>
</head>

<body>

    <div class="header">
        <h1>Produkte bewerten</h1>
    </div>

    <div class="topnav">
        <a href="logout.php">Logout</a>
        <a href="warenkorb.php">Warenkorb</a>
        <a href="ratings.php">Produkte bewerten</a>
        <a href="home.php">Startseite</a>
    </div>

    <div class="row">
        <center>
            <?php
            if (isset($_GET['e']) && $_GET['e'] == "1") {
                //Verbindung Datenbank
                $connection = mysqli_connect("", "root");

                //Datenbank auswählen
                mysqli_select_db($connection, "webshop");

                //Abfrage Text --> hinzufügen einer neuen Bewertung
                $sql = "insert komments (rating, komment, fk_user, fk_product) values ('" . $_POST["sterne"] . "', '" . $_POST["comment"] . "', '" . $_SESSION["user_nr"] . "', '" . @$_SESSION['bier']['Pr_Nummer'] . "')";

                //SQL-Abfrage
                $result = mysqli_query($connection, $sql);

                //Anzahl der Datensätze ermitteln
                $num = mysqli_affected_rows($connection);

                if ($num == 1) {
                    //Bestätigung
                    echo "<p style='color: green;'>Dein Kommentar wurde erfolgreich hinzugefügt!</p>";
                } else {
                    //Fehlermeldung
                    echo "<p style='color: red;'>Fehler beim hinzufügen der Bewertung!</p>";
                }
                //löschen der Bier-Session
                unset($_SESSION['bier']);
            }

            if (!isset($_POST['ansehen']) && !isset($_POST['bewerten']) && !isset($_POST['bier'])) {
                //Standard Auswahl: Bewerten oder Ansehen
                echo "<form action='ratings.php' method='post'>";
                echo "<h2>Was möchtest du tun?</h2>";
                echo "<input class='kasse' style='border: none;' type='submit' name='bewerten' value='ein Produkt bewerten'>    ";
                echo "    <input class='kasse' style='border: none;' type='submit' name='ansehen' value='Bewertungen ansehen'>";
                echo "</form>";
            } elseif (isset($task['bewerten']) && !isset($_POST['bier'])) {
                //Auswahl  aus Produktliste
                echo "<h2>Welches Produkt möchtest du bewerten?</h2>";
                //Aufruf des Produkte-Funktion
                produkte($products, $task);
            } elseif (isset($task['ansehen'])  && !isset($_POST['bier'])) {
                //Auswahl  aus Produktliste
                echo "<h2>Von welchem Produkt möchtest du dir die Bewertungen ansehen?</h2>";
                //Aufruf des Produkte-Funktion
                produkte($products, $task);
            } elseif (isset($task['bewerten']) && isset($_POST['bier'])) {
                //Bewerten eines Bieres
                bewerten($_POST['bier']);
            } elseif (isset($task['ansehen']) && isset($_POST['bier'])) {
                //ansehen aller Bewertungen eines Bieres
                ansehen($_POST['bier']);
            }
            ?>
        </center>
    </div>



</body>
<footer>
    <p>Copyright &copy; 2020 Brero Webshops. All Rights Reserved</p>
</footer>


</html>