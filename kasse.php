<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <title>Kasse</title>

    <?php
    if (
        isset($_SESSION['name']) && isset($_SESSION['login']) && $_SESSION['login'] == "ok" &&
        isset($_SESSION['warenkorb']) && $_SESSION['user_type'] = "user"
    ) {


        $adresse = array();

        //Verbindung Datenbank
        $connection = mysqli_connect("", "root");

        //Datenbank auswählen
        mysqli_select_db($connection, "webshop");

        //Abfrage Text
        $sql = "select * from user where user_nr='" . $_SESSION['user_nr'] . "'";

        //SQL-Abfrage
        $result = mysqli_query($connection, $sql);

        while ($dsatz = mysqli_fetch_assoc($result)) {
            $adresse = $dsatz;
        }

        //Verbindung trennen
        mysqli_close($connection);
    } else {
        header("Location: login.php");
    }
    ?>
</head>

<body>

    <div class="header">
        <h1>Kasse</h1>
    </div>

    <div class="topnav">
        <a href="logout.php">Logout</a>
        <a href="warenkorb.php">Zum Warenkorb</a>
        <a href="home.php">Zu den Produkten</a>
    </div>

    <div class="row">
        <center>
            <h3>Bitte bestätige ob deine Lieferadresse mit deiner Rechnungsadresse übereinstimmt. <br>
                Falls nicht nehme hier Änderungen vor!
            </h3>

            <?php

            if (isset($_COOKIE['name']) || isset($_COOKIE['postcode']) || isset($_COOKIE['place']) || isset($_COOKIE['street']) || isset($_COOKIE['nr'])) {
                echo "<p style='color: green;'>Wir haben uns deine vorherige Adresse gemerkt! Bitte prüfe sie erneut.</p>";
            }

            ?>

            <form action="bestellung.php" method="post">
                <table>
                    <tr>
                        <td>
                            <input type="radio" id="rechnung" name="adresse" value="gleich" required>
                            <label for="rechnung">Lieferadresse = Rechnungsadresse</label>
                        </td>
                        <td>
                            <input type="radio" id="kreditkarte" name="adresse" value="geändert" required>
                            <label for="kreditkarte">andere Lieferadresse</label>
                        </td>
                    </tr>
                </table>

                <table border='1'>
                    <tr>
                        <td>Name an der Klingel</td>
                        <td> <input type="text" name="name" required value="<?php if (isset($_COOKIE['name'])) {
                                                                                echo $_COOKIE['name'];
                                                                            } else {
                                                                                echo $adresse['name'];
                                                                            } ?>" size="30">
                        </td>
                    </tr>
                    <tr>
                        <td>Postleitzahl</td>
                        <td><input type="text" name="postcode" required value="<?php if (isset($_COOKIE['postcode'])) {
                                                                                    echo $_COOKIE['postcode'];
                                                                                } else {
                                                                                    echo $adresse['postcode'];
                                                                                } ?>" size="30">
                        </td>
                    </tr>
                    <tr>
                        <td>Ort</td>
                        <td><input type="text" name="place" required value="<?php if (isset($_COOKIE['place'])) {
                                                                                echo $_COOKIE['place'];
                                                                            } else {
                                                                                echo $adresse['place'];
                                                                            } ?>" size="30">
                        </td>
                    </tr>
                    <tr>
                        <td>Straße</td>
                        <td><input type="text" name="street" required value="<?php if (isset($_COOKIE['street'])) {
                                                                                    echo $_COOKIE['street'];
                                                                                } else {
                                                                                    echo $adresse['street'];
                                                                                } ?>" size="30">
                        </td>
                    </tr>
                    <tr>
                        <td>Hausnummer</td>
                        <td><input type="text" name="nr" required value="<?php if (isset($_COOKIE['nr'])) {
                                                                                echo $_COOKIE['nr'];
                                                                            } else {
                                                                                echo $adresse['h_nr'];
                                                                            } ?>" size="30">
                        </td>
                    </tr>
                </table>
                <br>

                <h3>Wähle eine Zahlungsmethode:</h3>
                <table>
                    <tr>
                        <td>
                            <input type="radio" id="rechnung" name="zahlungsmethode" value="Rechnung" required>
                            <label for="rechnung">Rechnung</label>
                        </td>
                        <td>
                            <input type="radio" id="kreditkarte" name="zahlungsmethode" value="Kreditkarte" required>
                            <label for="kreditkarte">Kreditkarte</label>
                        </td>
                        <td>
                            <input type="radio" id="vorkasse" name="zahlungsmethode" value="Vorkasse" required>
                            <label for="vorkasse">Vorkasse</label>
                        </td>
                        <td>
                            <input type="radio" id="paypal" name="zahlungsmethode" value="PayPal" required>
                            <label for="paypal">PayPal</label>
                        </td>
                    </tr>
                </table>
                <br>
                <input class="kasse" style="border: none;" type="submit" value="Kostenpflichtig bestellen">
            </form>
        </center>
    </div>



</body>
<footer>
    <p>Copyright &copy; 2020 Brero Webshops. All Rights Reserved</p>
</footer>


</html>