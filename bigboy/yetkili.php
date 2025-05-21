<?php
session_start();
if (!isset($_SESSION["yetkili"]) || $_SESSION["yetkili"] != 1) {
    header("Location: yetkili_giris.php");
    exit();
}

require_once "baglanti.php";

if (isset($_GET['cikis'])) {
    $_SESSION["yetkili"] = 0;
    session_destroy();
    header("Location: yetkili_giris.php");
    exit();
}

if (isset($_GET['siparis_sil'])) {
    $siparis_id = intval($_GET['siparis_sil']);
    $sql = "DELETE FROM siparisler WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $siparis_id);
    $stmt->execute();
    header("Location: yetkili.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['siparis_guncelle'])) {
    $siparis_id = intval($_POST['siparis_id']);
    $order_status = htmlspecialchars($_POST['order_status']);

    $sql = "UPDATE siparisler SET order_status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $order_status, $siparis_id);
    $stmt->execute();
    header("Location: yetkili.php");
    exit();
}

if (isset($_GET['yemek_sil'])) {
    $yemek_id = intval($_GET['yemek_sil']);
    $sql = "DELETE FROM yemekler WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $yemek_id);
    $stmt->execute();
    header("Location: yetkili.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['yemek_ekle'])) {
    $yemek_adi = htmlspecialchars($_POST['yemek_adi']);
    $fiyat = floatval($_POST['fiyat']);

    $sql = "INSERT INTO yemekler (yemek_adi, fiyat) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sd", $yemek_adi, $fiyat);
    $stmt->execute();
    header("Location: yetkili.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['yemek_guncelle'])) {
    $yemek_id = intval($_POST['yemek_id']);
    $yemek_adi = htmlspecialchars($_POST['yemek_adi']);
    $fiyat = floatval($_POST['fiyat']);

    $sql = "UPDATE yemekler SET yemek_adi = ?, fiyat = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdi", $yemek_adi, $fiyat, $yemek_id);
    $stmt->execute();
    header("Location: yetkili.php");
    exit();
}

$sql_siparisler = "SELECT * FROM siparisler ORDER BY created_at DESC";
$result_siparisler = $conn->query($sql_siparisler);

$sql_yemekler = "SELECT * FROM yemekler";
$result_yemekler = $conn->query($sql_yemekler);

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yetkili Paneli</title>
    <link rel="stylesheet" href="css/yetkili.css">
</head>
<body>
    <div class="header">
        <h2>Yetkili Paneli</h2>
        <a href="?cikis=true" class="logout-button">Çıkış Yap</a>
    </div>

    <h3>Tüm Siparişler</h3>
    <table>
        <tr>
            <th>Sipariş Numarası</th>
            <th>İsim</th>
            <th>Telefon</th>
            <th>Adres</th>
            <th>Yemekler</th>
            <th>Toplam Fiyat</th>
            <th>Ödeme Tipi</th>
            <th>Sipariş Durumu</th>
            <th>Sipariş Tarihi</th>
            <th>İşlemler</th>
        </tr>
        <?php while ($siparis = $result_siparisler->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($siparis['order_number']); ?></td>
                <td><?php echo htmlspecialchars($siparis['name']); ?></td>
                <td><?php echo htmlspecialchars($siparis['phone']); ?></td>
                <td><?php echo htmlspecialchars($siparis['address']); ?></td>
                <td>
                    <?php
                    $food_ids = explode(',', $siparis['food_ids']);
                    $food_names = [];
                    foreach ($food_ids as $food_id) {
                        $food_id = intval($food_id);
                        $food_sql = "SELECT yemek_adi FROM yemekler WHERE id = ?";
                        $food_stmt = $conn->prepare($food_sql);
                        $food_stmt->bind_param("i", $food_id);
                        $food_stmt->execute();
                        $food_result = $food_stmt->get_result();
                        if ($food_result && $food_result->num_rows > 0) {
                            $food = $food_result->fetch_assoc();
                            $food_names[] = $food['yemek_adi'];
                        }
                    }
                    echo htmlspecialchars(implode(', ', $food_names));
                    ?>
                </td>
                <td><?php echo htmlspecialchars($siparis['total_price']); ?> TL</td>
                <td><?php echo htmlspecialchars($siparis['payment']); ?></td>
                <td>
                    <form method="POST" class="status-form">
                        <input type="hidden" name="siparis_id" value="<?php echo $siparis['id']; ?>">
                        <select name="order_status">
                            <option value="Siparişiniz Alındı" <?php if($siparis['order_status'] == "Siparişiniz Alındı") echo "selected"; ?>>Siparişiniz Alındı</option>
                            <option value="Sipariş Hazırlanıyor" <?php if($siparis['order_status'] == "Sipariş Hazırlanıyor") echo "selected"; ?>>Sipariş Hazırlanıyor</option>
                            <option value="Sipariş Yolda" <?php if($siparis['order_status'] == "Sipariş Yolda") echo "selected"; ?>>Sipariş Yolda</option>
                            <option value="Sipariş Teslim Edildi" <?php if($siparis['order_status'] == "Sipariş Teslim Edildi") echo "selected"; ?>>Sipariş Teslim Edildi</option>
                            <option value="Sipariş İptal Edildi" <?php if($siparis['order_status'] == "Sipariş İptal Edildi") echo "selected"; ?>>Sipariş İptal Edildi</option>
                        </select>
                        <button type="submit" name="siparis_guncelle">Güncelle</button>
                    </form>
                </td>
                <td><?php echo date('d-m-Y H:i:s', strtotime($siparis['created_at'])); ?></td>
                <td>
                    <a href="?siparis_sil=<?php echo $siparis['id']; ?>" onclick="return confirm('Bu siparişi silmek istediğinize emin misiniz?');" class="sil-link">Sil</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <h3>Mevcut Yemekler</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Yemek Adı</th>
            <th>Fiyat</th>
            <th>İşlemler</th>
        </tr>
        <?php while ($yemek = $result_yemekler->fetch_assoc()): ?>
            <tr>
                <form method="POST">
                    <td><?php echo htmlspecialchars($yemek['id']); ?></td>
                    <td><input type="text" name="yemek_adi" value="<?php echo htmlspecialchars($yemek['yemek_adi']); ?>"></td>
                    <td><input type="text" name="fiyat" value="<?php echo htmlspecialchars($yemek['fiyat']); ?>"></td>
                    <td>
                        <input type="hidden" name="yemek_id" value="<?php echo htmlspecialchars($yemek['id']); ?>">
                        <button type="submit" name="yemek_guncelle">Güncelle</button>
                        <a href="?yemek_sil=<?php echo htmlspecialchars($yemek['id']); ?>" onclick="return confirm('Bu yemeği silmek istediğinize emin misiniz?');" class="sil-link">Sil</a>
                    </td>
                </form>
            </tr>
        <?php endwhile; ?>
        <tr>
            <form method="POST">
                <td>Yeni</td>
                <td><input type="text" name="yemek_adi" placeholder="Yemek Adı" required></td>
                <td><input type="text" name="fiyat" placeholder="Fiyat" required></td>
                <td><button type="submit" name="yemek_ekle">Ekle</button></td>
            </form>
        </tr>
    </table>
</body>
</html>
