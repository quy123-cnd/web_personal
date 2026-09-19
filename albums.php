<?php

require_once "connect.php";

$visible_column = $conn->query("SHOW COLUMNS FROM albums LIKE 'is_visible'");

if ($visible_column->rowCount() === 0) {

    $conn->exec(
        "ALTER TABLE albums
         ADD is_visible TINYINT(1) NOT NULL DEFAULT 1"
    );
}

$category_names = [
    "cuoi" => "Wedding Collection",
    "giadinh" => "Family Collection",
    "nghethuat" => "Art Collection",
    "makeup" => "Bridal Makeup Collection"
];

$albums_result = $conn->query(
    "SELECT id, title, category, image_url, description
     FROM albums
     WHERE is_visible = 1
     ORDER BY id DESC"
);

$albums = [];

while ($album = $albums_result->fetch()) {

    $albums[] = $album;
}

$album_images = [];

$images_result = $conn->query(
    "SELECT album_id, image_url
     FROM album_images
     ORDER BY id ASC"
);

while ($image = $images_result->fetch()) {

    $album_images[$image["album_id"]][] = $image["image_url"];
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bộ Sưu Tập</title>
    <link rel="stylesheet" href="css/albums.css?v=4">
   
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
                <li><a href="services.php" class="nav-link">Dịch Vụ - Gói giá</a></li>
                <li><a href="albums.php" class="nav-link active" >Bộ Sưu Tập</a></li>
                <li><a href="contact.php" class="nav-link">Liên hệ</a></li>
            </ul>
        </nav>
    </header>

    <nav class="mobile-bottom-nav" aria-label="Điều hướng chính">
        <a href="home.php" class="mobile-nav-link">
            <i class="fa-solid fa-house" aria-hidden="true"></i>
            <span>Trang chủ</span>
        </a>
        <a href="albums.php" class="mobile-nav-link active">
            <i class="fa-regular fa-images" aria-hidden="true"></i>
            <span>Sưu tập</span>
        </a>
        <a href="contact.php" class="mobile-nav-link">
            <i class="fa-regular fa-heart" aria-hidden="true"></i>
            <span>Liên hệ</span>
        </a>
        <a href="services.php" class="mobile-nav-link">
            <i class="fa-solid fa-tag" aria-hidden="true"></i>
            <span>Bảng giá</span>
        </a>
    </nav>

    <section class="collections">
        <div class="mini-title">COLLECTIONS</div>
        <div class="big-title">BỘ SƯU TẬP <span id="category-name">Ảnh cưới</span></div>

        <div class="albums-tabs" >
            <button type="button" class="tab-btn active" data-category="cuoi" data-name="Ảnh cưới">ẢNH CƯỚI</button>
            <button type="button" class="tab-btn" data-category="giadinh" data-name="Ảnh gia đình">ẢNH GIA ĐÌNH</button>
            <button type="button" class="tab-btn" data-category="nghethuat" data-name="Ảnh nghệ thuật">ẢNH NGHỆ THUẬT</button>
            <button type="button" class="tab-btn" data-category="makeup" data-name="Makeup">MAKEUP</button>
        </div>
    </section>

    <section class="grid-card">

        <?php foreach ($albums as $album): ?>

            <div
                class="album-card"
                data-category="<?= htmlspecialchars($album["category"]) ?>"
                data-card="album-<?= $album["id"] ?>"
                <?= $album["category"] !== "cuoi" ? "hidden" : "" ?>
            >
                <img
                    src="<?= htmlspecialchars($album["image_url"]) ?>"
                    alt="<?= htmlspecialchars($album["title"]) ?>"
                >

                <div class="album-info">
                    <h3><?= htmlspecialchars($album["title"]) ?></h3>
                    <p>
                        <?= htmlspecialchars(
                            $category_names[$album["category"]]
                            ?? $album["category"]
                        ) ?>
                    </p>
                </div>
            </div>

        <?php endforeach; ?>

    </section>



    <div class="album-modal" id="albumModal">

        <div class="modal-content">

            <button class="modal-close" type="button">
                ×
            </button>

            <h2 id="modalTitle">ẢNH CỔNG</h2>

            <?php foreach ($albums as $album): ?>

                <div
                    class="image-list"
                    data-card="album-<?= $album["id"] ?>"
                    hidden
                >
                    <?php

                    $images = $album_images[$album["id"]]
                        ?? [$album["image_url"]];

                    foreach ($images as $image_url):

                    ?>
                        <img
                            src="<?= htmlspecialchars($image_url) ?>"
                            alt="<?= htmlspecialchars($album["title"]) ?>"
                        >
                    <?php endforeach; ?>
                </div>

            <?php endforeach; ?>
        </div>
    </div>

    <div class="image-modal" id="image-modal">
        <button class="image-modal-close">x</button>
        <img id="fullImage" src="" alt="ảnh chi tiết">
    </div>






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
                <li><a href="services.php" class="nav-link">Dịch Vụ - Gói giá</a></li>
                <li><a href="albums.php" class="nav-link active">Bộ Sưu Tập</a></li>
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




    <script src="js/albums.js"></script>
</body>
</html>