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
        $sql_kat = "select * from kategorie";

        //SQL-Abfrage
        $result = mysqli_query($connection, $sql_kat);

        //Anzahl der Datensätze ermitteln
        $num = mysqli_num_rows($result);

        //Array aller Kategorien & Produkte
        $kategorien = array();
        $products = array();
        $j = 0;

        while ($dsatz = mysqli_fetch_assoc($result)) {
            $kategorien[$j] = $dsatz;
            $j++;
        }

        // echo "<pre>";
        // print_r($kategorien);
        // echo "</pre>";





        //BEARBEITUNG VON KATEGORIEN UND PRODUKTEN
        //---------------Kategorien--------------------
        //---------------Kategorie löschen-------------
        if (isset($_POST['loeschen'])) {
            //Datenbank auswählen
            mysqli_select_db($connection, "webshop");

            //Abfrage vorbereitung
            $sql = "delete from kategorie where name='" . $_POST['kat_name_del'] . "'";

            mysqli_query($connection, $sql);

            mysqli_close($connection);

            //----------------Kategorie erstellen--------------------------
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
            //echo "Es wurden " . $num . " erstellt.";
            mysqli_close($connection);

            //---------------------Kategorie bearbeiten------------------------
        } elseif (isset($_POST['bearbeiten'])) {
            //Datenbank auswählen
            mysqli_select_db($connection, "webshop");

            //Abfrage vorbereiten
            $sql = "update kategorie set name='" . $_POST['kat_name_neu'] . "'where name ='" . $_POST['kat_name'] . "'";


            //SQL-Abfrage
            mysqli_query($connection, $sql);


            mysqli_close($connection);

            //-------------------------------------------------------------------------------------
            //-------------------Aendern der Produkte----------------------------
            //--------------------Produkt loeschen------------------------------
        } elseif (isset($_POST['p_loeschen'])) {
            //Datenbank auswählen
            mysqli_select_db($connection, "webshop");

            //Abfrage vorbereiten 
            $sql = "delete from product where name='" . $_POST['prod_name_del'] . "'";

            //SQL-Abfrage
            mysqli_query($connection, $sql);


            mysqli_close($connection);

            //-------------------------Produkt erstellen-----------------------------
        } elseif (isset($_POST['p_erstellen'])) {
            mysqli_close($connection);
            //Verbindung aufnehmen
            $connection = mysqli_connect("", "root");

            //Datenbank auswählen
            mysqli_select_db($connection, "webshop");

            foreach ($kategorien as $var => $key) {
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

            foreach ($kategorien as $var => $key) {
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
        //elseif(isset($_POST['sel_cat'])){
        //     foreach ($kategorien as $var => $key) {
        //         foreach ($key as $cat =>$bname) {
        //             if ($cat == "name"&& $bname==$_POST['sel_cat']) {
        //                 $sql = "insert product (name) values('" . $_POST["new_prod_name"] . "', '" . $_POST["price"] . "', '".$_POST["sel_cat"]."','".$_POST["new_prod_origin"]."','".$_POST["exp_date"]."')";

        //             }}}

        // }
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

                echo "<form action='admin.php' method='post'><input type='text' name='prod_name_del' required> <br/ ><br/ > <input  type='submit'  value='Produkt Löschen' name='p_loeschen' class='kasse' style='border: none;' >";
                echo "<br/></form></center>";


                //Bereich für das Anlegen von neuen Produkten
            } elseif (isset($_GET['k']) && $_GET['k'] == 5) {
                echo "<center>
                    <p><h2>Hier Können Sie Produkte Anlegen!</h2></p></center></br >";

                echo "<center>Geben Sie den Namen des neuen Produkts an.<br/><br/><form action='admin.php' method='post'><input type='text' name='new_prod_name' required><br/><br/>";

                echo "Geben Sie den Preis des neuen Produktes an <br/><br/><input type='number' name='price' min='0' value='0' step='.01'required><br/><br/>";

                // echo "<pre>";
                // print_r($kategorien);
                // echo "</pre>";

                //--------------------------test---------------------
                echo "Geben Sie die Kategorie des neuen Produktes an <br/><br/><select name='sel_cat'>";
                foreach ($kategorien as $var => $key) {

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
            } elseif (isset($_GET['k']) && $_GET['k'] == 6) {
                echo "<center>
                <p><h2>Hier Können Sie Produkte Anlegen!</h2></p></center></br >";

                echo "<center>Geben Sie den Namen des zu ändernden Produkts an.<br/><br/><form action='admin.php' method='post'><input type='text' name='prod_name' required><br/><br/>";
                echo "Geben sie den neuen Namen des Produktes an.<br/><br/><input type='text' name='prod_name_neu' required><br/><br/>";

                echo "Geben Sie den neuen Preis des Produktes an <br/><br/><input type='number' name='change_price' min='0' value='0' step='.01'required><br/><br/>";

                //--------------------------test---------------------
                echo "Geben Sie neue die Kategorie des Produktes an <br/><br/><select name='sel_cat_new'>";
                foreach ($kategorien as $var => $key) {

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