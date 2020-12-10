<?php
//Logout inclusive löschen der Session
session_start();
session_destroy();
header("Location: home.php");