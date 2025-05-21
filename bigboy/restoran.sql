-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1:3306
-- Üretim Zamanı: 24 Eki 2024, 18:26:20
-- Sunucu sürümü: 8.3.0
-- PHP Sürümü: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `restoran`
--
CREATE DATABASE IF NOT EXISTS `restoran` DEFAULT CHARACTER SET utf8mb3 COLLATE utf8mb3_turkish_ci;
USE `restoran`;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `siparisler`
--

DROP TABLE IF EXISTS `siparisler`;
CREATE TABLE IF NOT EXISTS `siparisler` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb3_turkish_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb3_turkish_ci NOT NULL,
  `address` text COLLATE utf8mb3_turkish_ci NOT NULL,
  `food_ids` text COLLATE utf8mb3_turkish_ci NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `payment` varchar(50) COLLATE utf8mb3_turkish_ci NOT NULL,
  `order_number` varchar(255) COLLATE utf8mb3_turkish_ci NOT NULL,
  `order_status` text COLLATE utf8mb3_turkish_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_turkish_ci;

--
-- Tablo döküm verisi `siparisler`
--

INSERT INTO `siparisler` (`id`, `name`, `phone`, `address`, `food_ids`, `total_price`, `payment`, `order_number`, `order_status`, `created_at`) VALUES
(17, 'Egewqwfqwfas', 'Deneme', 'asfsa', '2', 60.00, 'kapida', '671a8fb0d4c6c', 'Sipariş Hazırlanıyor', '2024-10-24 18:19:28');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `yemekler`
--

DROP TABLE IF EXISTS `yemekler`;
CREATE TABLE IF NOT EXISTS `yemekler` (
  `id` int NOT NULL AUTO_INCREMENT,
  `yemek_adi` varchar(255) COLLATE utf8mb3_turkish_ci NOT NULL,
  `fiyat` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_turkish_ci;

--
-- Tablo döküm verisi `yemekler`
--

INSERT INTO `yemekler` (`id`, `yemek_adi`, `fiyat`) VALUES
(1, 'Cheeseburger', 50.00),
(2, 'Pizza Special', 60.00),
(3, 'Biftek Menü', 100.00),
(4, 'İskender', 70.00);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `yetkililer`
--

DROP TABLE IF EXISTS `yetkililer`;
CREATE TABLE IF NOT EXISTS `yetkililer` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kullaniciadi` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `parola` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kullaniciadi` (`kullaniciadi`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `yetkililer`
--

INSERT INTO `yetkililer` (`id`, `kullaniciadi`, `parola`) VALUES
(1, 'admin', '123');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
