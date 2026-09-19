
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";



CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



INSERT INTO `admins` (`id`, `username`, `password_hash`, `created_at`) VALUES
(1, 'admin', '$2y$10$SoOgeVcZtxyWrRz2k7Q//uX/emzNrToBBa9Hto4fmyGSC2Do2Q1Pi', '2026-09-08 08:11:09');



CREATE TABLE `albums` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(150) NOT NULL,
  `category` varchar(50) NOT NULL,
  `image_url` text NOT NULL,
  `description` varchar(255) DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_visible` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `albums` (`id`, `title`, `category`, `image_url`, `description`, `created_at`, `is_visible`) VALUES
(1, 'Ảnh cổng', 'cuoi', 'https://res.cloudinary.com/b5mc8oun/image/upload/v1788836147/cong4.jpg', 'Wedding Collection', '2026-09-11 04:39:11', 1),
(2, 'Ảnh phông trơn', 'cuoi', 'https://res.cloudinary.com/b5mc8oun/image/upload/v1788766132/IMG_9435.jpg', 'Wedding Collection', '2026-09-11 04:39:11', 1),
(3, 'Ảnh gia đình', 'giadinh', 'images/giadinh1.jpg', 'Family Collection', '2026-09-11 04:39:11', 1),
(4, 'Ảnh nghệ thuật', 'nghethuat', 'images/nghethuat2.jpg', 'Art Collection', '2026-09-11 04:39:11', 1),
(6, 'Makeup cô dâu', 'makeup', 'images/sect2.jpg', 'Bridal Makeup Collection', '2026-09-11 04:39:11', 1),
(8, 'Ngoại cảnh Tết1', 'nghethuat', 'https://res.cloudinary.com/b5mc8oun/image/upload/v1789106480/l5sojq2c10c33bdaak2p.jpg', 'tết 2025', '2026-09-11 06:03:32', 1),
(10, 'cô dâu hoàng tuấn', 'cuoi', 'https://res.cloudinary.com/b5mc8oun/image/upload/v1789193486/dfn7sgayamkxtjfrf2mp.jpg', '', '2026-09-12 06:13:29', 1),
(11, 'Ngọc Phiếu', 'nghethuat', 'https://res.cloudinary.com/b5mc8oun/image/upload/v1789193913/adbjnktszozujduq5ta0.jpg', '', '2026-09-12 06:17:49', 1);



CREATE TABLE `album_images` (
  `id` int(11) NOT NULL,
  `album_id` int(11) NOT NULL,
  `image_url` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `album_images` (`id`, `album_id`, `image_url`, `created_at`) VALUES
(1, 8, 'https://res.cloudinary.com/b5mc8oun/image/upload/v1789106480/l5sojq2c10c33bdaak2p.jpg', '2026-09-11 06:03:32'),
(2, 8, 'https://res.cloudinary.com/b5mc8oun/image/upload/v1789106480/p1z9l4prbsvwviof9ndd.jpg', '2026-09-11 06:03:32'),
(3, 8, 'https://res.cloudinary.com/b5mc8oun/image/upload/v1789106480/qlzr0eebnyiiuhzbj6b5.jpg', '2026-09-11 06:03:32'),
(4, 8, 'https://res.cloudinary.com/b5mc8oun/image/upload/v1789106480/sreraoqt732xkmj5de2v.jpg', '2026-09-11 06:03:32'),
(6, 10, 'https://res.cloudinary.com/b5mc8oun/image/upload/v1789193486/dfn7sgayamkxtjfrf2mp.jpg', '2026-09-12 06:13:29'),
(10, 11, 'https://res.cloudinary.com/b5mc8oun/image/upload/v1789193913/adbjnktszozujduq5ta0.jpg', '2026-09-12 06:20:42'),
(11, 11, 'https://res.cloudinary.com/b5mc8oun/image/upload/v1789193912/spaivqq1ptdqro3a64lh.jpg', '2026-09-12 06:20:42'),
(12, 11, 'https://res.cloudinary.com/b5mc8oun/image/upload/v1789193912/oc3ekzb9cibxojdgho6v.jpg', '2026-09-12 06:20:42');



CREATE TABLE `contacts` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `info_advise` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `packages` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `category` varchar(50) NOT NULL,
  `price` varchar(50) DEFAULT NULL,
  `badge` varchar(100) DEFAULT NULL,
  `quote` varchar(255) DEFAULT NULL,
  `features` text DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



INSERT INTO `packages` (`id`, `name`, `category`, `price`, `badge`, `quote`, `features`, `active`) VALUES
(1, 'Gói Ảnh Cổng 1', 'cuoi', '2000000', 'PHỔ BIẾN NHẤT', 'Lựa chọn tinh tế cho album kỷ niệm', 'Chụp ảnh 1 layout tại Studio\r\n01 váy VIP hoặc 01 váy Simple\r\n01 vest cao cấp\r\nMakeup & làm tóc', 1),
(2, 'Gói Concept Thanh Xuân', 'cuoi', '1200000', '⭐', '\"Lưu giữ trọn vẹn những khoảnh khắc ngọt ngào nhất\"', 'Chụp ảnh 2 concept tự chọn tại Studio\r\n\r\nTặng kèm toàn bộ file gốc sau buổi chụp\r\n\r\nHỗ trợ phụ kiện và hoa cầm tay miễn phí\r\n\r\nChỉnh sửa hoàn thiện 10 bức ảnh đẹp nhất', 1),
(4, 'okokoko', 'giadinh', '1200000', 'âsaasas', '1111111111111', 'kùngupanda', 1);


ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

ALTER TABLE `albums`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `album_images`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);




ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;


ALTER TABLE `albums`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;


ALTER TABLE `album_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;


ALTER TABLE `contacts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;


ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

