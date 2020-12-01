<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <title>Login</title>

    <?php
    if (isset($_POST["mail"]) && isset($_POST["password"])) {
        //Verbindung aufnehmen
        $connection = mysqli_connect("", "root");

        //Datenbank auswählen
        mysqli_select_db($connection, "webshop");

        //Abfrage Text
        $sql = "select * from user where email ='" . $_POST["mail"] . "'";

        //SQL-Abfrage
        $result = mysqli_query($connection, $sql);

        //Anzahl der Datensätze ermitteln
        $num = mysqli_num_rows($result);

        while ($dsatz = mysqli_fetch_assoc($result)) {
            if ($dsatz["email"] == $_POST["mail"] && $dsatz["pw"] == $_POST["password"]) {

                $_SESSION['name'] = $dsatz["fname"];
                $_SESSION['login'] = "ok";

                //Verbindung schließen
                mysqli_close($connection);
                header("Location: home.php");
                exit;
            } elseif ($num > 1) {
                continue;
            } else {
                mysqli_close($connection);
                header("Location: login.php?f=1");
                exit;
            }
        }

        if ($num == 0) {
            mysqli_close($connection);
            header("Location: login.php?f=2");
            exit;
        }
    } else {
    ?>
</head>

<body>

    <div class="header">
        <h1>Willkommen!</h1>
        <h3>Bitte melde dich an um fortzufahren:</h3>
    </div>

    <div class="login">
        <center>
            <span class="material-icons">
                account_circle
            </span>
            <?php
            if (isset($_GET['f']) && $_GET['f'] == 1) {
                echo "<p style='color: red;'>Fehler: <br> E-Mail und Passwort stimmen nicht überein!</p>";
            } elseif (isset($_GET['f']) && $_GET['f'] == 2) {
                echo "<p style='color: red;'>Fehler: <br> Es ist kein Nutzer mit dieser E-Mail vorhanden! Registriere dich um fortzufahren.</p>";
            }
            ?>
            <form action="login.php" method="post">
                <input class="" type="text" name="mail" placeholder="E-Mail" required />
                <br>
                <input class="" type="password" name="password" placeholder="Password" required />
                <br>
                <button class="" type="submit">Log In</button>
                <br>
            </form>
            <br>
            <p>Passwort vergessen? Hier können Sie es <a href="reset.php">zurücksetzen.</a></p>
            <p>Noch keinen Account? Hier können Sie sich <a href="registration.php">registrieren.</a></p>

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