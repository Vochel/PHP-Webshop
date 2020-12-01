<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="styles.css">
    <title>Passwort reset</title>
</head>

<body>

    <div class="header">
        <h1>PW reset</h1>
    </div>

    <div class="login">
        <center>
            <form action="login.php" method="post">
                <input type="text" name="hersteller" required size="20">
                <br>
                <input type="text" name="typ" required size="20">
                <br>
                <input type="text" name="gb" required size="20">
                <br>
                <input type="text" name="preis" required size="20">
                <br>
                <input type="text" name="artikelnummer" required size="20">
                <br>
                <input type="text" name="datum" required size="20">
                <br>
                <input type="submit" value="Daten absenden">
                <input type="reset" value="Daten zurücksetzen">
                <br>
            </form>
        </center>
    </div>
</body>
<footer>
    <p>Copyright &copy; 2020 Brerik Webshops. All Rights Reserved</p>
</footer>


</html>