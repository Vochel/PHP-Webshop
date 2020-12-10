<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="styles.css">
    <title>Bestellbestätigung</title>

    <?php
    //lädt Seite nur, falls Login ok und User Type = User && Warenkorb gefüllt
    if (
        isset($_SESSION['name']) && isset($_SESSION['login']) && $_SESSION['login'] == "ok" &&
        isset($_SESSION['warenkorb']) && $_SESSION['user_type'] = "user"
    ) {
        //falls andere Lieferadresse angegeben, schreibe Änderunge in Cookes
        if (isset($_POST['adresse']) && $_POST['adresse'] == "geändert") {
            setcookie("name", $_POST['name'], time() + 9999999);
            setcookie("postcode", $_POST['postcode'], time() + 9999999);
            setcookie("place", $_POST['place'], time() + 9999999);
            setcookie("street", $_POST['street'], time() + 9999999);
            setcookie("nr", $_POST['nr'], time() + 9999999);
        }
        // echo "<pre>";
        // print_r($_SESSION);
        // echo "</pre>";

        // echo "<pre>";
        // print_r($_POST);
        // echo "</pre>";

        //falls Login nicht ok, oder Warenkorb leer, oder Nicht Type User --> Redirect zum Login
    } else {
        header("Location: login.php");
    }
    ?>
</head>

<body>

    <div class="header">
        <h1>Bestellbestätigung</h1>
    </div>

    <div class="row">
        <center>
            <!-- Begrüßung mit Namen -->
            <h3>Hallo <?php if (!empty($_SESSION['name'])) {
                            echo $_SESSION['name'];
                        } ?>!</h3>
            <br>
            <h4>Deine Bestellung mit der Nummer:
                <!-- Erzeugt persönliche Bestellnummer -->
                <?php echo " " . $_SESSION['user_nr'] .  $_SESSION['user_nr'] - 1 .  $_SESSION['user_nr'] + 4 . $_SESSION['user_nr'] + 9 . $_SESSION['user_nr'] + 1 . $_SESSION['user_nr'] + 2 . " " ?>
                ist bei uns eingegangen.
            </h4>
            <!-- Zeigt gewählte Zahlungsmthode -->
            <h4>Die Bezahlung über <?php echo $_POST['zahlungsmethode'] ?> war erfogreich.</h4>
            <h4>Wir versenden deine Artikel an folgende Adresse:</h4>
            <br>
            <!-- zeigt gewählte Lieferadresse -->
            <p><?php echo $_POST['street'], " ", $_POST['nr']; ?> <br>
                <?php echo $_POST['postcode'], " ", $_POST['place']; ?></p>

            <br>
            <br>
            <p>Viel Spaß damit und bis bald! <br> Dein Brero Biershop Team</p>
            <br>
            <br>
            <br>

            <form action="home.php" method="post"><input class="kasse" style="border: none;" type="submit"
                    name="bestellt" value="Zurück zur Startseite">
            </form>

        </center>
    </div>



</body>
<footer>
    <p>Copyright &copy; 2020 Brero Webshops. All Rights Reserved</p>
</footer>


</html>