<?php
    define("host", "localhost");
    define("user", "root");
    define("pass", "");
    define("db_sec", "restoran");
    define("db_port", 3306);
    $conn = mysqli_connect(host, user, pass, db_sec, db_port);
    $temp = mysqli_query($conn, "set names 'utf8'");
    if (!$conn) {
        die("Veritabanı bağlantı hatası: " . mysqli_connect_error());
    }
?>
