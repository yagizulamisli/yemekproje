<?php
session_start();
require_once "baglanti.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kullaniciadi = htmlspecialchars($_POST["kullaniciadi"]);
    $parola = htmlspecialchars($_POST["parola"]);
    $sql = "SELECT * FROM yetkililer WHERE kullaniciadi = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $kullaniciadi);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($parola === $user['parola']) {
            $_SESSION["yetkili"] = 1;
            header("Location: yetkili.php");
            exit();
        } else {
            $error = "Yanlış kullanıcı adı veya parola!";
        }
    } else {
        $error = "Yanlış kullanıcı adı veya parola!";
    }
} else {
    if (isset($_SESSION["yetkili"]) && $_SESSION["yetkili"] == 1) {
        header("Location: yetkili.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8" />
    <title>Yetkili Giriş</title>
    <link rel="stylesheet" type="text/css" href="css/style_yetkili.css">
</head>
<body>
    <form action="" method="post">
        <h2>Yetkili Paneli</h2>
        <?php if (isset($error)) { ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>
        <input type="text" name="kullaniciadi" placeholder="Kullanıcı Adı" />
        <input type="password" name="parola" placeholder="Parola" />
        <button type="submit">Giriş Yap</button>
        <a href="siparis.php" class="ca">Geri Dön</a>
    </form>
</body>
</html>
