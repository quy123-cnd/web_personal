<?php
$contact_status = $_GET["sent"] ?? $_GET["error"] ?? "";
?>

<!DOCTYPE html>   
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên Hệ</title>
    <link rel="stylesheet" href="css/contact.css?v=8">
   
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300..700;1,300..700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Italianno&display=swap" rel="stylesheet">

    <!-- icon-đt, youtu, ig, ...-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=location_on" />

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
                <li><a href="albums.php" class="nav-link " >Bộ Sưu Tập</a></li>
                <li><a href="contact.php" class="nav-link active">Liên hệ</a></li>
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
        <a href="contact.php" class="mobile-nav-link active">
            <i class="fa-regular fa-heart" aria-hidden="true"></i>
            <span>Liên hệ</span>
        </a>
        <a href="services.php" class="mobile-nav-link">
            <i class="fa-solid fa-tag" aria-hidden="true"></i>
            <span>Bảng giá</span>
        </a>
    </nav>

    <div class="contact">
        <div class="container-contact">
            <div class="contact-title">
                <div class="big-title">LIÊN HỆ VỚI <span>với chúng tôi</span></div>
                <div class="gach"></div>
            </div>

            <div class="info-contact">
                <a class="info" href="https://maps.app.goo.gl/Qwx9C4inpQMcNobW7?g_st=iz" target="_blank">
                    <span class="material-symbols-outlined">location_on</span>
                    <p>390, khu phố Phú chiến, phường Phú Khương, tỉnh Vĩnh Long</p>
                </a>
                <a class="info">
                    <span class="fa-solid fa-phone"></span>
                    <p>Số điện thoại của chúng tôi: 08.222.348.22 - 08.567.567.92</p>
                </a>
                <a class="info" href="https://www.facebook.com/HoangTuanStudioBenTre?locale=vi_VN" target="_blank">
                    <span class="fa-brands fa-facebook"></span>
                    <p>Liên hệ qua Facebook</p>
                </a>
                <div class="open-daily">
                    <div class="open">GIỜ MỞ CỬA: <span>8:00-19:00 _ Daily</span></div>
                </div>
            </div>

            
        </div>

        <form class="form" action="save_contact.php"  method="POST">
            <?php if ($contact_status === "1"): ?>
                <p class="form-message success">Gửi thông tin thành công. Nếu sau 24h bạn không nhận được phản hồi, hãy liên hệ chúng tôi trực tiếp qua zalo hoặc facebook.</p>
            <?php elseif ($contact_status === "missing"): ?>
                <p class="form-message error">Vui lòng nhập đầy đủ họ tên, số điện thoại và thông tin cần tư vấn.</p>
            <?php elseif ($contact_status === "phone"): ?>
                <p class="form-message error">Số điện thoại không hợp lệ.</p>
            <?php endif; ?>

            <label>Họ và tên</label>
            <input type="text" id="name" name="name" placeholder="Tên của bạn" required>
            <label>Số điện thoại</label>
            <input type="tel" id="phone" name="phone" placeholder="Số điện thoại" required>
            <label>Thông tin cần tư vấn</label>
            <textarea id="info-advise" name="info-advise" rows="4" required></textarea>
            <button>Gửi thông tin</button>
        </form>
    </div>



    <footer>
        <div class="footer-content">
            <div class="footer-name">
                <h3>HOÀNG TUẤN STUDIO</h3>
            </div>
            <a href="https://maps.app.goo.gl/Qwx9C4inpQMcNobW7?g_st=iz" target="_blank">
    
                <p>📍 390, khu phố Phú chiến, phường Phú Khương, tỉnh Vĩnh Long</p>
            </a>
            
            <ul class="footer-nav">
                <li><a href="home.php" class="nav-link ">Trang Chủ</a></li>
                <li><a href="services.php" class="nav-link">Dịch Vụ - Gói giá</a></li>
                <li><a href="albums.php" class="nav-link ">Bộ Sưu Tập</a></li>
                <li><a href="contact.php" class="nav-link active">Liên hệ</a></li>
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




    <script src="js/contact.js"></script>
</body>
</html>