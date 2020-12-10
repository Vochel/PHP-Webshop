<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <title>Passwort reset</title>

    <?php
    if (isset($_POST["mail"]) && isset($_POST["pw"])) {
        //Verbindung aufnehmen
        $connection = mysqli_connect("", "root");

        //Datenbank auswählen
        mysqli_select_db($connection, "webshop");

        //Abfrage Text --> PW updaten
        $sql = "update user set pw='" . $_POST["pw"] . "' where email='" . $_POST["mail"] . "'";

        //SQL-Abfrage
        $result = mysqli_query($connection, $sql);

        //Anzahl der betroffenen Datensätze ermitteln
        $num = mysqli_affected_rows($connection);

        if ($num == 1) {
            //Verbindung schließen
            mysqli_close($connection);
            //Redirect zum Login mit Hinweis erfolgreich zurückgesetzt
            header("Location: login.php?e=2");
            exit;
        } else {
            //Verbindung schließen
            mysqli_close($connection);
            //wirft Fehlermeldung
            header("Location: reset.php?f=1");
            exit;
        }
    } else {
    ?>
</head>

<body>

    <div class="header">
        <h1>Passwort zurücksetzen</h1>
    </div>

    <div class="login">
        <center>
            <span class="material-icons">
                account_circle
            </span>
            <?php
            if (isset($_GET['f']) && $_GET['f'] == 1) {
                //Fehlermeldung: PW-Reset fehlgeschlagen
                echo "<p style='color: red;'>Fehler: <br> Passwortänderung fehlgeschlagen.</p>";
            }
            ?>
            <form action="reset.php" method="post">
                <input type="text" name="mail" required placeholder="E-Mail" size="30">
                <br>
                <input type="password" name="pw" required placeholder="neues Passwort" size="30">
                <br>
                <br>
                <input class='kasse' style='border: none;' type="submit" value="Passwort zurücksetzen">
                <br>
            </form>
            <br>
            <p>Da kannst dich wieder erinnern? Zurück zum <a href="login.php">Log In</a></p>

        </center>
    </div>
</body>
<footer>
    <p>Copyright &copy; 2020 Brero Webshops. All Rights Reserved</p>
</footer>

<?php
    }
?>

</html>