<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="styles.css">
    <title>Willkommen</title>
</head>

<body>
    <div class="header">

        <h1>Hallo <?php if (!empty($_SESSION['name'])) {
                        echo $_SESSION['name'];
                    } ?>, willkommen auf dem Webshop!</h1>
    </div>

    <div class="topnav">
        <a href="#">Thema #1</a>
        <a href="#">Thema #2</a>
        <a href="#">Thema #3</a>
    </div>

    <div class="row">
        <div class="column side">
            <h2>Side</h2>
            <ul class="categories">
                <a href="#">
                    <li>Kategorie #1</li>
                </a>
                <a href="#">
                    <li>Kategorie #2</li>
                </a>
                <a href="#">
                    <li>Kategorie #3</li>
                </a>
                <a href="#">
                    <li>Kategorie #4</li>
                </a>
                <a href="#">
                    <li>Kategorie #5</li>
                </a>
            </ul>
        </div>

        <div class="column middle">
            <h2>Main Content</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas sit amet pretium urna. Vivamus
                venenatis velit nec neque ultricies, eget elementum magna tristique. Quisque vehicula, risus eget
                aliquam placerat, purus leo tincidunt eros, eget luctus quam orci in velit. Praesent scelerisque tortor
                sed accumsan convallis.</p>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas sit amet pretium urna. Vivamus
                venenatis velit nec neque ultricies, eget elementum magna tristique. Quisque vehicula, risus eget
                aliquam placerat, purus leo tincidunt eros, eget luctus quam orci in velit. Praesent scelerisque tortor
                sed accumsan convallis.</p>
        </div>
    </div>


</body>
<footer>
    <p>Copyright &copy; 2020 Brerik Webshops. All Rights Reserved</p>
</footer>

</html>