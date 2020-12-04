<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <title>Registrierung</title>

    <?php
    if (isset($_POST["mail"]) && isset($_POST["pw"]) && isset($_POST["name"]) && isset($_POST["fname"]) && isset($_POST["bdate"]) && isset($_POST["postcode"]) && isset($_POST["place"]) && isset($_POST["street"]) && isset($_POST["nr"])) {
        //Verbindung aufnehmen
        $connection = mysqli_connect("", "root");

        //Datenbank auswählen
        mysqli_select_db($connection, "webshop");

        //Abfrage Text
        $sql = "insert user (email, pw, name, fname, bdate, postcode, place, street, h_nr) values ('" . $_POST["mail"] . "', '" . $_POST["pw"] . "', '" . $_POST["name"] . "', '" . $_POST["fname"] . "', '" . $_POST["bdate"] . "', '" . $_POST["postcode"] . "', '" . $_POST["place"] . "', '" . $_POST["street"] . "', '" . $_POST["nr"] . "')";

        //SQL-Abfrage
        $result = mysqli_query($connection, $sql);

        //Anzahl der betroffenen Datensätze ermitteln
        $num = mysqli_affected_rows($connection);

        if ($num == 1) {
            //Verbindung schließen
            mysqli_close($connection);
            header("Location: login.php?e=1");
            exit;
        } else {
            //Verbindung schließen
            mysqli_close($connection);
            header("Location: registration.php?f=1");
            exit;
        }
    } else {
    ?>
</head>

<body>

    <div class="header">
        <h1>Registrierung!</h1>
    </div>

    <div class="login">
        <center>
            <span class="material-icons">
                account_circle
            </span>
            <?php
            if (isset($_GET['f']) && $_GET['f'] == 1) {
                echo "<p style='color: red;'>Fehler: <br> Registrierung fehlgeschlagen! Versuche es erneut.</p>";
            }
            ?>
            <form action="registration.php" method="post">
                <input type="text" name="mail" required placeholder="E-Mail" size="30">
                <br>
                <input type="password" name="pw" required placeholder="Passwort" size="30">
                <br>
                <input type="text" name="name" required placeholder="Nachname" size="30">
                <br>
                <input type="text" name="fname" required placeholder="Vorname" size="30">
                <br>
                <input type="text" name="bdate" required placeholder="Geburtsdatum (YYYY-MM-DD)" size="30">
                <br>
                <input type="text" name="postcode" required placeholder="Postleitzahl" size="30">
                <br>
                <input type="text" name="place" required placeholder="Ort" size="30">
                <br>
                <input type="text" name="street" required placeholder="Straße" size="30">
                <br>
                <input type="text" name="nr" required placeholder="Hausnummer" size="30">
                <br>
                <br>
                <input type="submit" value="Jetzt registrieren">
                <br>
            </form>
            <br>
            <p>Hast du doch schon ein Account? Zurück zum <a href="login.php">Log In</a></p>

        </center>
    </div>
</body>
<footer>
    <p>Copyright &copy; 2020 Brerik Webshops. All Rights Reserved</p>
</footer>

<?php
    }
?>

</html>