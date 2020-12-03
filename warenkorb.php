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

        echo "<pre>";
        print_r($_SESSION);
        echo "</pre>";

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

        echo "<pre>";
        print_r($products);
        echo "</pre>";

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
                echo "<p style='color: green;'>Dein Warenkorb ist gefüllt!.</p>";
            }
            ?>
        </center>
    </div>



</body>
<footer>
    <p>Copyright &copy; 2020 Brerik Webshops. All Rights Reserved</p>
</footer>


</html>