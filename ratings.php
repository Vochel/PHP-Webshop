<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="styles.css">
    <title>Bewertungen</title>

    <?php
    if (
        isset($_SESSION['name']) && isset($_SESSION['login']) && $_SESSION['login'] == "ok" &&
        $_SESSION['user_type'] = "user"
    ) {

        echo "<pre>";
        print_r($_SESSION);
        echo "</pre>";

        echo "<pre>";
        print_r($_POST);
        echo "</pre>";
    } else {
        header("Location: login.php");
    }
    ?>
</head>

<body>

    <div class="header">
        <h1>Produkte bewerten</h1>
    </div>

    <div class="topnav">
        <a href="logout.php">Logout</a>
        <a href="warenkorb.php">Warenkorb</a>
        <a href="home.php">Startseite</a>
    </div>

    <div class="row">
        <center>


        </center>
    </div>



</body>
<footer>
    <p>Copyright &copy; 2020 Brero Webshops. All Rights Reserved</p>
</footer>


</html>