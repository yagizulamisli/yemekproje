<?php
require_once "baglanti.php";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_order'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $foods = $_POST['foods'];
    $payment = $_POST['payment'];
    $order_number = uniqid();

    $food_ids = implode(',', $foods);
    $total_price = 0;

    foreach ($foods as $food_id) {
        $result = $conn->query("SELECT fiyat FROM yemekler WHERE id=$food_id");
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $total_price += $row['fiyat'];
        }
    }

    $order_status = "Siparişiniz alındı";

    $sql = "INSERT INTO siparisler (name, phone, address, food_ids, total_price, payment, order_number, order_status) 
            VALUES ('$name', '$phone', '$address', '$food_ids', '$total_price', '$payment', '$order_number', '$order_status')";

    if ($conn->query($sql) === TRUE) {
        $order_created = true;
    } else {
        echo "Hata: " . $sql . "<br>" . $conn->error;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['query_order'])) {
    $order_id = $_POST['order_id'];

    $sql = "SELECT order_number, created_at, order_status FROM siparisler WHERE order_number='$order_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $order_info = $result->fetch_assoc();
    } else {
        $order_not_found = true;
    }
}


?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sipariş Formu</title>
    <link rel="stylesheet" href="css/siparis.css">
</head>

<body>

    <?php if (isset($order_created) && $order_created): ?>
        <div class="order-status">
            Siparişiniz alındı! Sipariş numaranız: <span id="orderNumber"><?php echo $order_number; ?></span>
        </div>
    <?php endif; ?>

    <?php if (isset($order_info)): ?>
        <div class="query-result">
            <p>Sipariş ID: <?php echo $order_info['order_number']; ?></p>
            <p>Sipariş Tarihi: <?php echo date('d-m-Y H:i:s', strtotime($order_info['created_at'])); ?></p>
            <p>Sipariş Durumu: <?php echo $order_info['order_status']; ?></p>
        </div>
    <?php elseif (isset($order_not_found) && $order_not_found): ?>
        <div class="query-result">
            <p>Sipariş bulunamadı. Lütfen sipariş numaranızı kontrol edin.</p>
        </div>
    <?php endif; ?>

    <div class="order-form">
        <a href="index.html">
            <img src="img/logo.jpg" alt="Logo">
        </a>

        <h2>Sipariş Formu</h2>

        <form id="orderForm" method="POST">
            <input type="text" name="name" placeholder="İsim Soyisim" required>
            <input type="tel" name="phone" placeholder="Telefon" required>
            <textarea name="address" placeholder="Adres Bilgisi" required></textarea>

            <label for="foods">Yemek Seçiniz:</label>
            <div class="food-container">
                <?php
                require_once "baglanti.php";
                $sql = "SELECT id, yemek_adi, fiyat FROM yemekler";
                $result = $conn->query($sql);

                if ($result === FALSE) {
                    echo "Sorgu hatası: " . $conn->error . "<br>";
                } else {
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "
                        <div class='food-item'>
                            <input type='checkbox' name='foods[]' value='{$row['id']}' data-price='{$row['fiyat']}' class='food-checkbox'>
                            <label>{$row['yemek_adi']} - {$row['fiyat']} TL</label>
                        </div>";
                        }
                    } else {
                        echo "Yemek bulunamadı.";
                    }
                }
                ?>
            </div>

            <div class="total-price">Toplam Fiyat: <span id="totalPrice">0</span> TL</div>

            <label for="payment">Ödeme Tipi:</label>
            <select name="payment" required>
                <option value="kapida">Kapıda Ödeme</option>
                <option value="kredi">Kredi Kartı (Şu an çalışmıyor)</option>
            </select>

            <button type="submit" name="submit_order">Siparişi Ver</button>

        </form>

        <div class="status-form">
            <h3>Sipariş Sorgula</h3>
            <form method="POST">
                <input type="text" name="order_id" placeholder="Sipariş Numarası" required>
                <button type="submit" name="query_order">Sorgula</button>
            </form>

            <br>

        </div>


    </div>
    <a href="yetkili_giris.php" class="adminButon">Yetkili Paneli</a>

    <script>
        const foodCheckboxes = document.querySelectorAll('.food-item input[type="checkbox"]');
        const totalPriceEl = document.getElementById('totalPrice');
        let totalPrice = 0;

        foodCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                totalPrice = 0;
                foodCheckboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        totalPrice += parseFloat(checkbox.getAttribute('data-price'));
                    }
                });
                totalPriceEl.textContent = totalPrice.toFixed(2);
            });
        });
    </script>

</body>

</html>