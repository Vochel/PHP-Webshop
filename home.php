<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="styles.css">
    <title>Willkommen</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <?php
    if (isset($_SESSION['name']) && isset($_SESSION['login']) && $_SESSION['login'] == "ok") {

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

        //Abfrage Text
        $sql_kat = "select name from kategorie";

        //SQL-Abfrage
        $result = mysqli_query($connection, $sql_kat);

        //Anzahl der Datensätze ermitteln
        $num = mysqli_num_rows($result);

        //Array aller Kategorien & Produkte
        $kategorien = array();
        $products = array();
        $j = 0;

        while ($dsatz = mysqli_fetch_assoc($result)) {
            $kategorien[$j] = $dsatz['name'];
            $j++;
        }


        if (isset($_POST['kategorie'])) {
            //Datenbank auswählen
            mysqli_select_db($connection, "webshop");

            //Abfrage Text
            $sql_kat = "select nummer from kategorie where name='" . $_POST['kategorie'] . "'";

            //SQL-Abfrage
            $result = mysqli_query($connection, $sql_kat);

            //Anzahl der Datensätze ermitteln
            $num = mysqli_num_rows($result);

            $kat_nr;

            if ($num == 1) {
                $dsatz = mysqli_fetch_assoc($result);
                $kat_nr = $dsatz['nummer'];

                //Abfrage Text
                $sql_prod = "select * from product where fk_kat='" . $kat_nr . "'";

                //SQL-Abfrage
                $result = mysqli_query($connection, $sql_prod);

                //Anzahl der Datensätze ermitteln
                $num = mysqli_num_rows($result);

                $o = 0;

                while ($dsatz = mysqli_fetch_assoc($result)) {
                    $products[$o] = $dsatz;
                    $o++;
                }
            } else {
                echo "Fehler beim abfragen der Kategorienummer!";
            }



            mysqli_close($connection);
        }

        // echo "<pre>";
        // print_r($_POST);
        // echo "</pre>";

        // echo "<pre>";
        // print_r($_SESSION);
        // echo "</pre>";
    } else {
        header("Location: login.php");
    }
    ?>
</head>

<body>
    <div class="header">

        <h1>Hallo <?php if (!empty($_SESSION['name'])) {
                        echo $_SESSION['name'];
                    } ?>, willkommen auf dem Webshop!</h1>
    </div>

    <div class="topnav">
        <a href="logout.php">Logout</a>
        <a href="warenkorb.php">Warenkorb</a>
    </div>

    <div class="row">
        <div class="column side">
            <h2>Kategorien</h2>
            <?php
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
            if (!empty($products)) {
                echo "<h2><u>" . $_POST['kategorie'] . "</u></h2>";
                echo "<form action='home.php?e=1' method='post'>";
                echo "<table border='1'> <tr class='table_head'><td>Marke</td><td>Herkunft</td><td>Preis pro Kiste</td><td>Anzahl</td></tr>";

                $prods = array();

                foreach ($products as $item) {
                    foreach ($item as $key => $value) {
                        $prods[$key] = $value;
                    }
                    echo "<tr><td> " . $prods['name'] . " </td><td> " . $prods['origin'] . "</td><td> " . $prods['price'] . "</td><td> <input type ='text' name='" . $prods['Pr_Nummer'] . "' placeholder='0'></td></tr>";
                }
                echo "</table><br>";
                echo "<input type='submit' value='In den Warenkorb'>";
                echo "</form>";
            } else {
                echo "<center><span class='material-icons'>
                        error_outline
                    </span>
                    <p>Wähle eine Kategorie!</p></center>";

                if (isset($_GET['e']) && $_GET['e'] == 1) {
                    echo "<center><p style='color: green;'>Deine gewählten Artikel wurden erfolgreich dem Warenkorb hinzugefügt!</p></center>";
                }
            }
            ?>
        </div>
    </div>


</body>
<footer>
    <p>Copyright &copy; 2020 Brerik Webshops. All Rights Reserved</p>
</footer>

</html>