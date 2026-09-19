<?php

require_once "connect.php";

$packages_result = $conn->query(
    "SELECT name, category, price, badge, quote, features
     FROM packages
        WHERE active = 1
     ORDER BY id ASC"
);

$packages_by_category = [];

while ($package = $packages_result->fetch()) {
    $packages_by_category[$package["category"]][] = $package;
}

$service_categories = [
    "cuoi" => "Ảnh Cưới",
    "giadinh" => "Ảnh Gia Đình",
    "nghethuat" => "Ảnh Nghệ Thuật",
    "makeup" => "Makeup Cô Dâu"
];

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dịch vụ</title>
    <link rel="stylesheet" href="css/services.css?v=5">
   
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300..700;1,300..700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Italianno&display=swap" rel="stylesheet">

    <!-- icon-đt, youtu, ig, ...-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"></head>


</head>

<body>
    <header id="service">
        <div class="logo">
            <div class="logo-image">
                <img src="https://res.cloudinary.com/b5mc8oun/image/upload/v1788836146/logo.jpg" alt="Logo">
                <h3>Hoàng Tuấn</h3>
            </div>
            <p>Studio</p>
        </div>

        <nav class="nav">
            <ul class="nav-list">
                <li><a href="home.php" class="nav-link">Trang Chủ</a></li>
                <li><a href="services.php" class="nav-link active">Dịch Vụ - Gói giá</a></li>
                <li><a href="albums.php" class="nav-link">Bộ Sưu Tập</a></li>
                <li><a href="contact.php" class="nav-link">Liên hệ</a></li>
            </ul>
        </nav>
    </header>

    <nav class="mobile-bottom-nav" aria-label="Điều hướng chính">
        <a href="home.php" class="mobile-nav-link">
            <i class="fa-solid fa-house" aria-hidden="true"></i>
            <span>Trang chủ</span>
        </a>
        <a href="albums.php" class="mobile-nav-link">
            <i class="fa-regular fa-images" aria-hidden="true"></i>
            <span>Sưu tập</span>
        </a>
        <a href="contact.php" class="mobile-nav-link">
            <i class="fa-regular fa-heart" aria-hidden="true"></i>
            <span>Liên hệ</span>
        </a>
        <a href="services.php" class="mobile-nav-link active">
            <i class="fa-solid fa-tag" aria-hidden="true"></i>
            <span>Bảng giá</span>
        </a>
    </nav>

    <section id="services" class="services">
        <div class="hero-service">
            <p>SERVICES</p>
            <h1>DỊCH VỤ CỦA CHÚNG TÔI</h1>
            <h2>Lưu giữ khoảnh khắc của bạn</h2>
            <h3>Mỗi khoảng đều đáng giá và cần được lưu giữ</h3>
        </div>
    </section>

    <section class="pricing-section">
        <div class="pricing-header">
            <p class="pricing-sub-title">— BẢNG GIÁ DỊCH VỤ —</p>
            
            <div class="category-tabs">
                <button class="tab-btn active" data="tab-cuoi" data-name="Ảnh cưới" >ẢNH CƯỚI</button>
                <button class="tab-btn" data="tab-giadinh" data-name="Ảnh gia đình">ẢNH GIA ĐÌNH</button>
                <button class="tab-btn" data="tab-nghethuat" data-name="Ảnh nghệ thuật">ẢNH NGHỆ THUẬT</button>
                <button class="tab-btn" data="tab-makeup" data-name="makeup">MAKEUP CÔ DÂU HỎI - CƯỚI</button>
            </div>
            <h1 class="pricing-title">Gói Dịch Vụ <span id="category-name">Ảnh Cưới</span></h1>
            <p class="pricing-desc">Gói dịch vụ được thiết kế riêng biệt, phù hợp với từng phong cách và ngân sách</p>
        </div>

        <?php foreach ($service_categories as $category => $category_name): ?>
            <div class="pricing-grid tab-content <?= $category === "cuoi" ? "active" : "" ?>" id="tab-<?= htmlspecialchars($category) ?>">
                <?php foreach ($packages_by_category[$category] ?? [] as $package): ?>
                    <div class="pricing-card">
                        <?php if ($package["badge"] !== null && trim($package["badge"]) !== ""): ?>
                            <div class="card-bag">✦ <?= htmlspecialchars($package["badge"]) ?></div>
                        <?php endif; ?>

                        <h3 class="card-title"><?= htmlspecialchars($package["name"]) ?></h3>
                        <div class="card-price">
                            <?= number_format((float) $package["price"], 0, ",", ".") ?>
                            <span class="currency">đ</span>
                        </div>

                        <?php if ($package["quote"] !== null && trim($package["quote"]) !== ""): ?>
                            <p class="card-quote" style="display: none;">&quot;<?= htmlspecialchars($package["quote"]) ?>&quot;</p>
                        <?php endif; ?>

                        <div class="card-service-label">📷 DỊCH VỤ</div>
                        <ul class="card-features">
                            <?php foreach (preg_split('/\r\n|\r|\n/', $package["features"] ?? "") as $feature): ?>
                                <?php if (trim($feature) !== ""): ?>
                                    <li><span class="check">✓</span> <?= htmlspecialchars(trim($feature)) ?></li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                        <a href="contact.php" class="tu-van">Tư vấn thêm</a>

                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </section>



    <footer>
        <div class="footer-content">
            <div class="footer-name">
                <h3>HOÀNG TUẤN STUDIO</h3>
            </div>
            <a href="https://maps.app.goo.gl/Qwx9C4inpQMcNobW7?g_st=iz" target="_blank">
                <!-- <span>📍</span> -->
                <p>📍 390, khu phố Phú chiến, phường Phú Khương, tỉnh Vĩnh Long</p>
            </a>
            
            <ul class="footer-nav">
                <li><a href="home.php" class="nav-link ">Trang Chủ</a></li>
                <li><a href="services.php" class="nav-link active">Dịch Vụ - Gói giá</a></li>
                <li><a href="albums.php" class="nav-link">Bộ Sưu Tập</a></li>
                <li><a href="contact.php" class="nav-link">Liên hệ</a></li>
            </ul>
        </div> 

        <div class="social-netwwork">
            <span class="divide"></span>
            <div class="social">
                <a class="span">
                    <span>•</span>
                    <p>Quy trình đặc quyền</p>
                </a>
                <a class="span">
                    <span>•</span>
                    <p>Trải nghiệm cá nhân hóa</p>
                </a>
                <a class="span">
                    <span>•</span>
                    <p>Hỗ trợ tận tâm</p>
                </a>
                <a class="span">
                    <span class="fa-solid fa-phone"></span>
                    <p>0822234822</p>
                </a>
                <a class="span" href="https://www.facebook.com/HoangTuanStudioBenTre?locale=vi_VN" target="_blank">
                    <span class="fa-brands fa-facebook"></span>
                    <p>Facebook</p>
                </a>
                <a class="span" href="https://zalo.me/0822234822" target="_blank">
                    <p>Zalo</p>
                </a>
            </div>
        </div>
    </footer> 
 


        <script src="js/services.js"></script>
</body>
</html>