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

            //Hier erfolgt die erstellung der Buttons welche zur bearbeitung der Kategorien genutzt werden
            //Bereich für löschen
            echo "<table>";
            echo "<tr><td><form action='admin.php?k=1' method='post'><input  type='submit'  value='Kategorie Löschen' class='kasse' style='border: none;'></form></td></tr>";

            //Bereich für erstellen

            echo "<tr><td><form action='admin.php?k=2' method='post'><input  type='submit'  value='Kategorie Erstelle' class='kasse'  style='border: none;'></form></td></tr>";

            //Bereich für aktualisieren

            echo "<tr><td><form action='admin.php?k=3' method='post'><input  type='submit'  value='Kategorie Aktualisieren'  class='kasse' style='border: none;'></form></td></tr>";
            echo "</table><br/ > <br/ ><br/ >";

            //-------------------------------------------------

            //Produkte erstellen löschen und bearbeiten
            echo " <h2>Produkte</h2>";

            //Bereich für löschen
            echo "<table>";
            echo "<tr><td><form action='admin.php?k=4' method='post'><input  type='submit'  value='Produkt Löschen' class='kasse' style='border: none;'></form></td></tr>";

            //Bereich für erstellen

            echo "<tr><td><form action='admin.php?k=5' method='post'><input  type='submit'  value='Produkt Erstelle' class='kasse'  style='border: none;'></form></td></tr>";

            //Bereich für aktualisieren

            echo "<tr><td><form action='admin.php?k=6' method='post'><input  type='submit'  value='Produkt Aktualisieren'  class='kasse' style='border: none;'></form></td></tr>";
            echo "</table><br/ > <br/ ><br/ >";
            ?>


        </div>

        <div class="column middle">
            <?php

            //formular zu Löschen einer Kategorie
            if (isset($_GET['k']) && $_GET['k'] == 1) {
                echo "<center>
                    <p><h2>Hier Können Sie Kategorien Löschen!</h2></p></center></br >";

                echo "<center>Geben sie die zu löschende Kategorie an.<br/><br/>";

                echo "<form action='admin.php' method='post'><input type='text' name='kat_name_del' required> <br/ ><br/ > <input  type='submit'  value='Kategorie Löschen' name='loeschen' class='kasse' style='border: none;' >";
                echo "<br/></form></center>";


                //Bereich für das Anlegen von neuen Kategoreien
            } elseif (isset($_GET['k']) && $_GET['k'] == 2) {
                echo "<center>
                    <p><h2>Hier Können Sie Kategorien Anlegen!</h2></p></center></br >";

                echo "<center>Geben sie den Namen der neuen Kategorie an.<br/><br/>";

                echo "<form action='admin.php' method='post'><input type='text' name='new_kat_name' required><br/><br/><input  type='submit'  value='Kategorie erstellen' name='erstellen' class='kasse' style='border: none;'>";
                echo "<br/></form></center>";

                //Bereich für das bearbeiten eines bereits existierenden Produktes
            } elseif (isset($_GET['k']) && $_GET['k'] == 3) {
                echo "<center>
                    <p><h2>Hier Können Sie Kategorien Änderen!</h2></p></center></br >";

                echo "<center>Geben sie den Namen der zu bearbeitenden Kategorie an.<br/>";

                echo "<form action='admin.php' method='post'><input type='text' name='kat_name' required><br/><br/><br/>";

                echo "Geben sie den neuen Namen der Kategorie an.<br/><br/>";
                echo "<input type='text' name='kat_name_neu' required><br/><br/><input  type='submit'  value='Kategorie Ändern' name='bearbeiten' class='kasse' style='border: none;'><br/>";
                echo "</form></center><br/> <br />";


                //-----------------------------------------------------------------------------------------------------------------------------------

                //Sollte ein Produkt geändert weden erfolgt dies im Nachfolgenden Bereich
            } elseif (isset($_GET['k']) && $_GET['k'] == 4) {
                echo "<center>
                    <p><h2>Hier Können Sie Produkte Löschen!</h2></p></center></br >";

                echo "<center>Geben Sie das zu löschende Produkt an.<br/><br/>";

                echo "<form action='admin.php' method='post'><input type='text' name='kat_name_del' required> <br/ ><br/ > <input  type='submit'  value='Produkt Löschen' name='p_loeschen' class='kasse' style='border: none;' >";
                echo "<br/></form></center>";


                //Bereich für das Anlegen von neuen Kategoreien
            } elseif (isset($_GET['k']) && $_GET['k'] == 5) {
                echo "<center>
                    <p><h2>Hier Können Sie Produkte Anlegen!</h2></p></center></br >";

                echo "<center>Geben sie den Namen des neuen Produkts an.<br/><br/>";

                echo "<form action='admin.php' method='post'><input type='text' name='new_kat_name' required><br/><br/><input  type='submit'  value='Produkt erstellen' name='p_erstellen' class='kasse' style='border: none;'>";
                echo "<br/></form></center>";

                //Bereich für das bearbeiten eines bereits existierenden Produktes
            } elseif (isset($_GET['k']) && $_GET['k'] == 6) {
                echo "<center>
                    <p><h2>Hier Können Sie Produkte Änderen!</h2></p></center></br >";

                echo "<center>Geben sie den Namen des zu bearbeitenden Produktes an.<br/>";

                echo "<form action='admin.php' method='post'><input type='text' name='kat_name' required><br/><br/><br/>";

                echo "Geben sie den neuen Namen der Kategorie an.<br/><br/>";
                echo "<input type='text' name='kat_name_neu' required><br/><br/><input  type='submit'  value='Produkt Ändern' name='p_bearbeiten' class='kasse' style='border: none;'><br/>";
                echo "</form></center><br/> <br />";
            }
            ?>
        </div>
    </div>



</body>
<footer>
    <p>Copyright &copy; 2020 Brero Webshops. All Rights Reserved</p>
</footer>


</html>