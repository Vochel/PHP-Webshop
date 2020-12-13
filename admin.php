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

        if (isset($_POST["side_btn"])) {
            unset($_SESSION['kategorie']);
            unset($_SESSION['kat_nr']);
        }



        //Verbindung Datenbank
        $connection = mysqli_connect("", "root");

        //Datenbank auswählen
        mysqli_select_db($connection, "webshop");

        //Abfrage Text für alle Kategorien
        $sql_kat = "select name from kategorie";
        $sql_kat_fill = "select * from kategorie";
        //SQL-Abfrage
        $result = mysqli_query($connection, $sql_kat);
        $result2 = mysqli_query($connection, $sql_kat_fill);
        //Anzahl der Datensätze ermitteln
        $num = mysqli_num_rows($result);

        //Array aller Kategorien & Produkte & Ratings
        $kategorien_fill = array();
        $kategorien = array();
        $products = array();
        $ratings = array();
        $j = 0;

        //ermittelte Kategorien werden in Kategorie Array geschrieben
        while ($dsatz = mysqli_fetch_assoc($result)) {
            $kategorien[$j] = $dsatz['name'];
            $j++;
        }

        //ermittelte Kategorien werden in Kategorie Array geschrieben welcher später für das erstellen von Produkten benötigt wird
        while ($dsatz2 = mysqli_fetch_assoc($result2)) {
            $kategorien_fill[$j] = $dsatz2;
            $j++;
        }

        function kategorieNummer()
        {
            //Datenbank auswählen
            $connection = mysqli_connect("", "root");

            //Datenbank auswählen
            mysqli_select_db($connection, "webshop");
            //Abfrage Text für Kategorienummer
            $sql_kat = "select nummer from kategorie where name='" . $_POST['kategorie'] . "'";

            //SQL-Abfrage
            $result = mysqli_query($connection, $sql_kat);

            //Anzahl der Datensätze ermitteln
            $num = mysqli_num_rows($result);

            $kat_nr = 0;
            $prod_nr = array();

            //falls  genau eine Kategorie gefunden, hole alle Produkte dieser Kategorie
            if ($num == 1) {
                $dsatz = mysqli_fetch_assoc($result);
                $kat_nr = $dsatz['nummer'];

                $_SESSION["kategorie"] = $_POST["kategorie"];
                $_SESSION["kat_nr"] = $kat_nr;
            }
        }

        if (isset($_POST['kategorie']) || (isset($_SESSION["kategorie"]) && isset($_SESSION["kat_nr"]))) {
            if (isset($_POST['kategorie'])) {
                kategorieNummer();
            }
            $kat_nr;
            if ((isset($_SESSION["kategorie"]) && isset($_SESSION["kat_nr"]))) {
                $kat_nr = $_SESSION["kat_nr"];
            }
            //Datenbank auswählen
            mysqli_select_db($connection, "webshop");

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
        }




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

        echo "<pre>";
        print_r($_POST);
        echo "</pre>";

        // echo "<pre>";
        // print_r($kategorien);
        // echo "</pre>";
        // echo "<pre>";
        // print_r($kategorien_fill);
        // echo "</pre>";

        // echo "<pre>";
        // print_r($products);
        // echo "</pre>";

        // echo "<pre>";
        // print_r($ratings);
        // echo "</pre>";

        echo "<pre>";
        print_r($_SESSION);
        echo "</pre>";

        /**BEARBEITUNG VON KATEGORIEN UND PRODUKTEN
             KATEGORIEN
                Kategorie löschen*/

        if (isset($_POST['bearbeiten']) && $_POST["bearbeiten"] == "Kategorie Löschen") {

            //Datenbank auswählen
            mysqli_select_db($connection, "webshop");

            //Abfrage vorbereitung
            $sql = "delete from kategorie where name='" . $_SESSION["kategorie"] . "'";

            $result = mysqli_query($connection, $sql);

            @$num = mysqli_affected_rows($result);
            if ($num == 1) {
                $_GET["e"] = 1;
            }
            mysqli_close($connection);
            header("Refresh:0");
            //Kategorie erstellen
        } elseif (isset($_POST['erstellen'])) {
            //Datenbank auswählen
            mysqli_select_db($connection, "webshop");

            //Abfrage vorbereiten
            $sql = "insert kategorie (name) values('" . $_POST["new_kat_name"] . "')";

            //SQL-Abfrage
            $result = mysqli_query($connection, $sql);

            //Ende der Abfrage
            mysqli_close($connection);

            //Kategorie bearbeiten
        } elseif (isset($_POST['bearbeiten'])) {
            //Datenbank auswählen
            mysqli_select_db($connection, "webshop");

            //Abfrage vorbereiten
            $sql = "update kategorie set name='" . $_POST['kat_name_neu'] . "'where name ='" . $_POST['kat_name'] . "'";


            //SQL-Abfrage
            mysqli_query($connection, $sql);


            mysqli_close($connection);

            //-------------------------------------------------------------------------------------
            //Aendern der Produkte
            //Produkt loeschen
        } elseif (isset($_POST['p_loeschen'])) {
            //Datenbank auswählen
            mysqli_select_db($connection, "webshop");

            //Abfrage vorbereiten 
            $sql = "delete from product where name='" . $_POST['prod_name_del'] . "'";

            //SQL-Abfrage
            mysqli_query($connection, $sql);


            mysqli_close($connection);

            //Produkt erstellen
        } elseif (isset($_POST['p_erstellen'])) {
            mysqli_close($connection);
            //Verbindung aufnehmen
            $connection = mysqli_connect("", "root");

            //Datenbank auswählen
            mysqli_select_db($connection, "webshop");

            foreach ($kategorien_fill as $var => $key) {
                foreach ($key as $cat => $bname) {

                    if ($cat == "name" && $_POST['sel_cat'] == $bname) {
                        $sql = "insert product (name, price, fk_kat, origin, exp_date) values ('" . $_POST["new_prod_name"] . "', '" . $_POST["price"] . "', '" . $key['Nummer'] . "', '" . $_POST["new_prod_origin"] . "', '" . $_POST["exp_date"] . "')";
                        //SQL-Abfrage
                        $result = mysqli_query($connection, $sql);
                    }
                }
            }

            //Anzahl der betroffenen Datensätze ermitteln
            $num = mysqli_affected_rows($connection);
            echo $num;

            mysqli_close($connection);
        } elseif (isset($_POST['p_bearbeiten'])) {
            //Datenbank auswählen
            mysqli_select_db($connection, "webshop");

            foreach ($kategorien_fill as $var => $key) {
                foreach ($key as $cat => $bname) {

                    if ($cat == "name" && $_POST['sel_cat_new'] == $bname) {
                        $sql = "update product set name='" . $_POST['prod_name_neu'] . "',price='" . $_POST['change_price'] . "',fk_kat ='" . $key['Nummer']  . "',origin='" . $_POST['change_prod_origin'] . "',exp_date='" . $_POST['change_exp_date'] . "' where name ='" . $_POST['prod_name'] . "'";

                        //SQL-Abfrage
                        $result = mysqli_query($connection, $sql);
                    }
                }
            }
            $num = mysqli_affected_rows($connection);
            echo $sql;
            echo $num;

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
            // anzeigen aller Kategorien, falls welche vorhanden/gefunden
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

            //Bereich für das erstellen von Kategorien

            echo "<tr><td><form action='admin.php?k=1'  method='post'><input  type='submit' name='side_btn'  value='Kategorie Erstelle' class='kasse'  style='border: none;'></form></td></tr>";

            //-------------------------------------------------

            //Produkte erstellen erstellen und bearbeiten

            //Bereich für erstellen

            echo "<tr><td><form action='admin.php?k=2' method='post'><input  type='submit' name='side_btn' value='Produkt Erstelle' class='kasse'  style='border: none;'></form></td></tr>";

            //Bereich für aktualisieren

            echo "<tr><td><form action='admin.php?k=3' method='post'><input  type='submit' name='side_btn' value='Produkt Aktualisieren'  class='kasse' style='border: none;'></form></td></tr>";
            echo "</table><br/ > <br/ ><br/ >";
            ?>


        </div>

        <div class="column middle">
            <?php
            // falls Producte gefunden und Kategorie ausgewählt, zeig diese an
            if (!empty($products)) {
                //kategorie als Überschrift
                echo "<h2><u>" . $_SESSION["kategorie"] . "</u></h2>";
                echo "<form action='home.php?e=1' method='post'>";
                echo "<table border='1'> <tr class='table_head'><td>Marke</td><td>Herkunft</td><td>Preis pro Kiste</td><td>Bewertungen</td></tr>";

                //erstellt Array mit aller Produkte
                $prods = array();

                //füllt Produkt Array und gibt diese in Tabelle aus
                foreach ($products as $item) {
                    foreach ($item as $key => $value) {
                        //füllt Produkte Array
                        $prods[$key] = $value;
                    }
                    echo "<tr><td> " . $prods['name'] . " </td><td> " . $prods['origin'] . "</td><td> " . $prods['price'] . "</td>";
                    echo "<td>" . bewertung($prods['Pr_Nummer'], $ratings) . "</td></tr>";
                }


                echo "</table><br>";

                echo "</form>";

                echo "<table><form action='admin.php'  method='post'>";
                echo "<tr><td><input  type='submit' name='bearbeiten' value='Kategorie Löschen' class='kasse' style='border: none;'></form></td>";

                echo "<td><input  type='submit' name='bearbeiten' value='Kategorie Aktualisieren'  class='kasse' style='border: none;'></form></td>";

                echo "<td><input  type='submit' name='bearbeiten' value='Produkt Löschen' class='kasse' style='border: none;'></form></td></tr>";

                echo "</table><br/><br/>";
            } /*else {
                echo "<center><span class='material-icons'>
                    error_outline
                </span>
                <p>Wähle eine Kategorie!</p></center>";
            }*/


            //Bereich für das Anlegen von neuen Kategoreien
            if (isset($_GET['k']) && $_GET['k'] == 1) {

                echo "<center>
                    <p><h2>Hier Können Sie Kategorien Anlegen!</h2></p></center></br >";

                echo "<center>Geben sie den Namen der neuen Kategorie an.<br/><br/>";

                echo "<form action='admin.php' method='post'><input type='text' name='new_kat_name' required><br/><br/><input  type='submit'  value='Kategorie erstellen' name='erstellen' class='kasse' style='border: none;'>";
                echo "<br/></form></center>";

                //Bereich für das bearbeiten eines bereits existierenden Produktes
                /* } elseif (isset($_GET['k']) && $_GET['k'] == 3) {
                echo "<center>
                    <p><h2>Hier Können Sie Kategorien Änderen!</h2></p></center></br >";

                echo "<center>Geben sie den Namen der zu bearbeitenden Kategorie an.<br/>";

                echo "<form action='admin.php' method='post'><input type='text' name='kat_name' required><br/><br/><br/>";

                echo "Geben sie den neuen Namen der Kategorie an.<br/><br/>";
                echo "<input type='text' name='kat_name_neu' required><br/><br/><input  type='submit'  value='Kategorie Ändern' name='bearbeiten' class='kasse' style='border: none;'><br/>";
                echo "</form></center><br/> <br />";*/


                //-----------------------------------------------------------------------------------------------------------------------------------

                //Sollte ein Produkt geändert weden erfolgt dies im Nachfolgenden Bereich
                /* } elseif (isset($_GET['k']) && $_GET['k'] == 4) {
                echo "<center>
                    <p><h2>Hier Können Sie Produkte Löschen!</h2></p></center></br >";

                echo "<center>Geben Sie das zu löschende Produkt an.<br/><br/>";

                echo "<form action='admin.php' method='post'><input type='text' name='prod_name_del' required> <br/ ><br/ > <input  type='submit'  value='Produkt Löschen' name='p_loeschen' class='kasse' style='border: none;' >";
                echo "<br/></form></center>";*/


                //Bereich für das Anlegen von neuen Produkten
            } elseif (isset($_GET['k']) && $_GET['k'] == 2) {
                echo "<center>
                    <p><h2>Hier Können Sie Produkte Anlegen!</h2></p></center></br >";

                echo "<center>Geben Sie den Namen des neuen Produkts an.<br/><br/><form action='admin.php' method='post'><input type='text' name='new_prod_name' required><br/><br/>";

                echo "Geben Sie den Preis des neuen Produktes an <br/><br/><input type='number' name='price' min='0' value='0' step='.01'required><br/><br/>";

                //--------------------------test---------------------
                echo "Geben Sie die Kategorie des neuen Produktes an <br/><br/><select name='sel_cat'>";
                foreach ($kategorien_fill as $var => $key) {

                    foreach ($key as $cat => $bname) {
                        if ($cat == "name") {
                            echo " <option valeue='" . $key['name'] . "'>" . $bname . "</option>     ";
                        }
                    }
                }



                echo "</select></br></br>";
                //---------------------test_ende---------------------




                echo "Geben Sie die Herkunft des neuen Produkts an.<br/><br/><input type='text' name='new_prod_origin' required><br/><br/>";
                echo "Geben Sie das Verfallsdatum des neuen Produktes an<br/><br/> <input type='date' name='exp_date'><br/><br/><input  type='submit'  value='Produkt erstellen' name='p_erstellen' class='kasse' style='border: none;'><br/></form></center>";


                //Bereich für das bearbeiten eines bereits existierenden Produktes
            } elseif (isset($_GET['k']) && $_GET['k'] == 3) {
                echo "<center>
                <p><h2>Hier Können Sie Produkte Anlegen!</h2></p></center></br >";

                echo "<center>Geben Sie den Namen des zu ändernden Produkts an.<br/><br/><form action='admin.php' method='post'><input type='text' name='prod_name' required><br/><br/>";
                echo "Geben sie den neuen Namen des Produktes an.<br/><br/><input type='text' name='prod_name_neu' required><br/><br/>";

                echo "Geben Sie den neuen Preis des Produktes an <br/><br/><input type='number' name='change_price' min='0' value='0' step='.01'required><br/><br/>";

                //--------------------------test---------------------
                echo "Geben Sie neue die Kategorie des Produktes an <br/><br/><select name='sel_cat_new'>";
                foreach ($kategorien_fill as $var => $key) {

                    foreach ($key as $cat => $bname) {
                        if ($cat == "name") {
                            echo " <option valeue='" . $key['name'] . "'>" . $bname . "</option>     ";
                        }
                    }
                }
                echo "</select></br></br>";
                //---------------------test_ende---------------------

                echo "Geben Sie die geänderte Herkunft des Produkts an.<br/><br/><input type='text' name='change_prod_origin' required><br/><br/>";
                echo "Geben Sie das neue Verfallsdatum des Produktes an<br/><br/> <input type='date' name='change_exp_date'><br/><br/><input  type='submit'  value='Produkt Bearbeiten' name='p_bearbeiten' class='kasse' style='border: none;'><br/></form></center>";
            }
            ?>
        </div>
    </div>



</body>
<footer>
    <p>Copyright &copy; 2020 Brero Webshops. All Rights Reserved</p>
</footer>


</html>