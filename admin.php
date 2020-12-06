<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <title>Admin</title>

    <?php
    if (isset($_SESSION['name']) && isset($_SESSION['login']) && $_SESSION['login'] == "ok" && $_SESSION['user_type'] == "admin") {

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

        // echo "<pre>";
        // print_r($kategorien);
        // echo "</pre>";

        // echo "<pre>";
        // print_r($_POST);
        // echo "</pre>";

        echo "<pre>";
        print_r($_POST);
        echo "</pre>";

        if (isset($_POST['kategorie'])) {
            //Datenbank auswählen
            mysqli_select_db($connection, "webshop");


            //Abfrage 
            $sql = "select nummer from kategorie where name='" . $_POST['kategorie'] . "'";

            //SQL-Abfrage

            $result = mysqli_query($connection, $sql);

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
        } elseif (isset($_POST['loeschen'])) {
            //Datenbank auswählen
            mysqli_select_db($connection, "webshop");

            //Abfrage vorbereitung
            $sql = "delete from kategorie where name='" . $_POST['kat_name_del'] . "'";

            //SQL-Abfrage
            $result = mysqli_query($connection, $sql);


            // $num = mysqli_affected_rows($result);

            //Ende der Abfrage
            // echo "Es wurden " . $num . " gelöscht.";
            mysqli_close($connection);
        } elseif (isset($_POST['erstellen'])) {
            //Datenbank auswählen
            mysqli_select_db($connection, "webshop");

            //Abfrage vorbereiten
            $sql = "insert kategorie (name) values('" . $_POST["new_kat_name"] . "')";

            //SQL-Abfrage
            $result = mysqli_query($connection, $sql);

            //Anzahl der betroffenen Datensätze ermitteln
            //$num = mysqli_affected_rows($result);

            //Ende der Abfrage
            echo "Es wurden " . $num . " erstellt.";
            mysqli_close($connection);
        } elseif (isset($_POST['bearbeiten'])) {
            //Datenbank auswählen
            mysqli_select_db($connection, "webshop");

            //Abfrage vorbereiten
            $sql = "update kategorie set name='" . $_POST['kat_name_neu'] . "'where name ='" . $_POST['kat_name'] . "'";

            //Abfragen


            //SQL-Abfrage
            $result = mysqli_query($connection, $sql);

            //$num = mysqli_affected_rows($result);
            //Ende der Abfrage
            echo "Es wurden " . $num . " geändert.";
            mysqli_close($connection);
        }
    } elseif (isset($_SESSION['name']) && isset($_SESSION['login']) && $_SESSION['login'] == "ok" && $_SESSION['user_type'] == "user") {
        header("Location: home.php");
    } else {
        header("Location: login.php");
    }

    ?>

</head>

<body>

    <div class="header">
        <h1>Admin Page</h1>
        <h2>Hallo <?php if (!empty($_SESSION['name'])) {
                        echo $_SESSION['name'];
                    } ?>!</h2>
    </div>

    <div class="topnav">
        <a href="logout.php">Logout</a>
    </div>

    <div class="row">
        <div class="column side">
            <h2>Kategorien</h2>
            <?php
            if (!empty($kategorien)) {
                echo "<ul class='categories'>";
                echo "<form action='admin.php' method='post'>";
                for ($i = 0; $i < count($kategorien); $i++) {
                    echo "<input class='btn' type='submit' name='kategorie' value='" . $kategorien[$i] . "'> <br>";
                }
                echo "</form>";
                echo "</ul>";
            } else {
                echo "Leider keine Kategorien gefunden";
            }
            //Hier erfolgt die erstellung der Buttons welche zur bearbeitung der Kategorien genutzt werden
            //Bereich für löschen
            echo "<table>";
            echo "<tr><td><form action='admin.php?k=1' method='post'><input  type='submit'  value='Kategorie Löschen' class='kasse' style='border: none;'></form></td></tr>";

            //Bereich für erstellen

            echo "<tr><td><form action='admin.php?k=2' method='post'><input  type='submit'  value='Kategorie Erstelle' class='kasse'  style='border: none;'></form></td></tr>";

            //Bereich für aktualisieren

            echo "<tr><td><form action='admin.php?k=3' method='post'><input  type='submit'  value='Kategorie Aktualisieren'  class='kasse' style='border: none;'></form></td></tr>";
            echo "</table>";

            ?>
        </div>

        <div class="column middle">
            <?php

            //formular zu Löschen einer Kategorie
            if (isset($_GET['k']) && $_GET['k'] == 1) {
                echo "<center>
                    <p>Hier Können Sie Kategorien Löschen!</p></center></br >";

                echo "<center>Geben sie die zu löschende Kategorie an.<br/><br/>";

                echo "<form action='admin.php' method='post'><input type='text' name='kat_name_del' required> <br/ ><br/ > <input  type='submit'  value='Kategorie Löschen' name='loeschen' class='kasse' style='border: none;' >";
                echo "<br/></form></center>";


                //Bereich für das Anlegen von neuen Kategoreien
            } elseif (isset($_GET['k']) && $_GET['k'] == 2) {
                echo "<center>
                    <p>Hier Können Sie Kategorien Anlegen!</p></center></br >";

                echo "<center>Geben sie den Namen der neuen Kategorie an.<br/><br/>";

                echo "<form action='admin.php' method='post'><input type='text' name='new_kat_name' required><br/><br/><input  type='submit'  value='Kategorie erstellen' name='erstellen' class='kasse' style='border: none;'>";
                echo "<br/></form></center>";

                //Bereich für das bearbeiten eines bereits existierenden Produktes
            } elseif (isset($_GET['k']) && $_GET['k'] == 3) {
                echo "<center>
                    <p>Hier Können Sie Kategorien Änderen!</p></center></br >";

                echo "<center>Geben sie den Namen der zu bearbeitenden Kategorie an.<br/>";

                echo "<form action='admin.php' method='post'><input type='text' name='kat_name' required><br/><br/><br/>";

                echo "Geben sie den neuen Namen der Kategorie an.<br/><br/>";
                echo "<input type='text' name='kat_name_neu' required><br/><br/><input  type='submit'  value='Kategorie Ändern' name='bearbeiten' class='kasse' style='border: none;'><br/>";
                echo "</form></center>";




                //Sollte ein Produkt geändert weden erfolgt dies im Nachfolgenden Bereich
            } else if (!empty($products)) {
                echo "<h2><u>" . $_POST['kategorie'] . "</u></h2>";
                echo "<form action='home.php?e=1' method='post'>";
                echo "<table border='1'> <tr class='table_head'><td>Produkt-Nr</td><td>Name</td><td>Preis pro Kiste</td><td>Kategorie</td><td>Herkunft</td><td>Ablaufdatum</td></tr>";

                $prods = array();

                foreach ($products as $item) {
                    foreach ($item as $key => $value) {
                        $prods[$key] = $value;
                    }
                    echo "<tr><td> " . $prods['Pr_Nummer'] . " </td><td> " . $prods['name'] . "</td><td> " . $prods['price'] . "</td><td> " . $prods['fk_kat'] . "</td><td> " . $prods['origin'] . "</td><td> " . $prods['exp_date'] . "</td></tr>";
                }
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
    <p>Copyright &copy; 2020 Brero Webshops. All Rights Reserved</p>
</footer>


</html>