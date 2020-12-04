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
        isset($_SESSION['warenkorb'])
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

            <form action="bestellung.php" methd="post">
                <table border='1'>
                    <tr>
                        <td>Name an der Klingel</td>
                        <td> <input type="text" name="name" required value="<?php echo $adresse['name']; ?>" size="30">
                        </td>
                    </tr>
                    <tr>
                        <td>Postleitzahl</td>
                        <td><input type="text" name="postcode" required value="<?php echo $adresse['postcode']; ?>"
                                size="30">
                        </td>
                    </tr>
                    <tr>
                        <td>Ort</td>
                        <td><input type="text" name="place" required value="<?php echo $adresse['place']; ?>" size="30">
                        </td>
                    </tr>
                    <tr>
                        <td>Straße</td>
                        <td><input type="text" name="street" required value="<?php echo $adresse['street']; ?>"
                                size="30">
                        </td>
                    </tr>
                    <tr>
                        <td>Hausnummer</td>
                        <td><input type="text" name="nr" required value="<?php echo $adresse['h_nr']; ?>" size="30">
                        </td>
                    </tr>
                </table>
                <br>
                <input type="submit" value="Kostenpflichtig bestellen">
            </form>
        </center>
    </div>



</body>
<footer>
    <p>Copyright &copy; 2020 Brerik Webshops. All Rights Reserved</p>
</footer>


</html>