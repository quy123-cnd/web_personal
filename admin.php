
<?php

require_once "connect.php";
require_once "authentication.php";
require_once "cloudinary.php";

$album_notice = "";
$album_error = "";

$album_status_messages = [
	"created" => "Đã thêm album thành công.",
	"updated" => "Đã cập nhật album thành công.",
	"deleted" => "Đã xóa album.",
	"toggled" => "Đã cập nhật trạng thái album."
];

$album_status = $_SESSION["album_status"] ?? "";
unset($_SESSION["album_status"]);

if (isset($album_status_messages[$album_status])) {
	$album_notice = $album_status_messages[$album_status];
}

$package_notice = "";
$package_error = "";

$package_status_messages = [
	"created" => "Đã thêm gói dịch vụ thành công.",
	"updated" => "Đã cập nhật gói dịch vụ thành công.",
	"deleted" => "Đã xóa gói dịch vụ.",
	"toggled" => "Đã cập nhật trạng thái gói dịch vụ."
];

$package_status = $_SESSION["package_status"] ?? "";
unset($_SESSION["package_status"]);

if (isset($package_status_messages[$package_status])) {
	$package_notice = $package_status_messages[$package_status];
}


// $conn->exec(
// 	"CREATE TABLE IF NOT EXISTS albums (
// 		id INT AUTO_INCREMENT PRIMARY KEY,
// 		title VARCHAR(255) NOT NULL,
// 		category VARCHAR(100) NOT NULL,
// 		image_url TEXT NOT NULL,
// 		description TEXT,
// 		created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
// 	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
// );

// $conn->exec(
// 	"CREATE TABLE IF NOT EXISTS album_images (
// 		id INT AUTO_INCREMENT PRIMARY KEY,
// 		album_id INT NOT NULL,
// 		image_url TEXT NOT NULL,
// 		created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
// 	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
// );

// $conn->exec(
// 	"CREATE TABLE IF NOT EXISTS packages (
// 		id INT AUTO_INCREMENT PRIMARY KEY,
// 		name VARCHAR(255) NOT NULL,
// 		category VARCHAR(100) NOT NULL,
// 		price DECIMAL(12, 0) NOT NULL,
// 		description VARCHAR(255),
// 		features TEXT,
// 		created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
// 	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
// );

$visible_column = $conn->query("SHOW COLUMNS FROM albums LIKE 'is_visible'");

if ($visible_column->rowCount() === 0) {

	$conn->exec(
		"ALTER TABLE albums
		 ADD is_visible TINYINT(1) NOT NULL DEFAULT 1"
	);
}

$package_features_column = $conn->query(
	"SHOW COLUMNS FROM packages LIKE 'features'"
);

if ($package_features_column->rowCount() === 0) {

	$conn->exec(
		"ALTER TABLE packages
		 ADD features TEXT NULL"
	);
}

$package_active_column = $conn->query(
	"SHOW COLUMNS FROM packages LIKE 'active'"
);

if ($package_active_column->rowCount() === 0) {

	$conn->exec(
		"ALTER TABLE packages
		 ADD active TINYINT(1) NOT NULL DEFAULT 1"
	);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

	$action = $_POST["action"] ?? "";

	if ($action === "save_album") {

		$album_id = (int) ($_POST["album_id"] ?? 0);

		$is_editing = $album_id > 0;

		$title = trim($_POST["album-title"] ?? "");

		$category = trim($_POST["album-category"] ?? "");

		$image_url = trim($_POST["image_url"] ?? "");

		$image_urls = json_decode(
			$_POST["image_urls"] ?? "[]",
			true
		);

		if (!is_array($image_urls)) {

			$image_urls = [];
		}

		$image_urls = array_values(
			array_filter($image_urls, "is_string")
		);

		if ($image_url !== "" && !in_array($image_url, $image_urls, true)) {

			array_unshift($image_urls, $image_url);
		}

		$image_url = $image_urls[0] ?? $image_url;

		$description = trim($_POST["album-description"] ?? "");

		if ($album_id > 0 && $image_url === "") {

			$current_stmt = $conn->prepare(
				"SELECT image_url FROM albums WHERE id = ?"
			);

			$current_stmt->execute([$album_id]);

			$current_album = $current_stmt->fetch();

			$image_url = $current_album["image_url"] ?? "";

			$current_stmt = null;
		}

		if ($title === "" || $category === "" || $image_url === "") {

			$album_error = "Vui lòng nhập đủ thông tin và chọn ảnh.";

		} else {

			if ($album_id > 0) {

				$stmt = $conn->prepare(
					"UPDATE albums
					 SET title = ?, category = ?, image_url = ?, description = ?
					 WHERE id = ?"
				);

				$stmt->execute([
					$title,
					$category,
					$image_url,
					$description,
					$album_id
				]);

			} else {

				$stmt = $conn->prepare(
					"INSERT INTO albums (title, category, image_url, description)
					 VALUES (?, ?, ?, ?)"
				);

				$stmt->execute([
					$title,
					$category,
					$image_url,
					$description
				]);
			}

			if ($stmt->rowCount() >= 0) {

				if ($album_id === 0) {

					$album_id = (int) $conn->lastInsertId();
				}

				if (count($image_urls) > 0) {

					$delete_images_stmt = $conn->prepare(
						"DELETE FROM album_images WHERE album_id = ?"
					);

					$delete_images_stmt->execute([$album_id]);

					$delete_images_stmt = null;

					$image_stmt = $conn->prepare(
						"INSERT INTO album_images (album_id, image_url)
						 VALUES (?, ?)"
					);

					foreach ($image_urls as $album_image_url) {

						$image_stmt->execute([$album_id, $album_image_url]);
					}

					$image_stmt = null;
				}

				$album_notice = $is_editing
					? "Đã cập nhật album thành công."
					: "Đã thêm album thành công.";

				$album_status = $is_editing ? "updated" : "created";

			} else {

				$album_error = "Không thể lưu album vào database.";
			}

			$stmt = null;

			if ($album_error === "") {
				$_SESSION["album_status"] = $album_status;
				header("Location: admin.php#albums");
				exit;
			}
		}
	}

	if ($action === "delete_album") {

		$album_id = (int) ($_POST["album_id"] ?? 0);

		$delete_images_stmt = $conn->prepare(
			"DELETE FROM album_images WHERE album_id = ?"
		);

		$delete_images_stmt->execute([$album_id]);

		$delete_images_stmt = null;

		$delete_album_stmt = $conn->prepare(
			"DELETE FROM albums WHERE id = ?"
		);

		$delete_album_stmt->execute([$album_id]);

		$delete_album_stmt = null;

		$_SESSION["album_status"] = "deleted";
		header("Location: admin.php#albums");
		exit;
	}

	if ($action === "toggle_album") {

		$album_id = (int) ($_POST["album_id"] ?? 0);

		$stmt = $conn->prepare(
			"UPDATE albums
			 SET is_visible = NOT is_visible
			 WHERE id = ?"
		);

		$stmt->execute([$album_id]);

		$stmt = null;

		$_SESSION["album_status"] = "toggled";
		header("Location: admin.php#albums");
		exit;
	}

	if ($action === "save_package") {
		$package_id = (int) ($_POST["package_id"] ?? 0);
		$package_name = trim($_POST["package-name"] ?? "");
		$package_category = trim($_POST["package-category"] ?? "");
		$package_price = trim($_POST["price"] ?? "");
		$package_badge = trim($_POST["badge"] ?? "");
		$package_quote = trim($_POST["quote"] ?? "");
		$package_features = trim($_POST["package-features"] ?? "");
		$valid_categories = [
			"cuoi",
			"giadinh",
			"nghethuat",
			"makeup"
		];

		if (
			$package_name === "" ||
			!in_array($package_category, $valid_categories, true) ||
			$package_price === "" ||
			!is_numeric($package_price) ||
			(float) $package_price < 0
		) {

			$package_error = "Vui lòng nhập tên, chọn danh mục và nhập giá hợp lệ.";

		} else {

			if ($package_id > 0) {

				$package_stmt = $conn->prepare(
					"UPDATE packages
					 SET name = ?, category = ?, price = ?, badge = ?, quote = ?, features = ?
					 WHERE id = ?"
				);

				$package_stmt->execute([
					$package_name,
					$package_category,
					$package_price,
					$package_badge,
					$package_quote,
					$package_features,
					$package_id
				]);

				$package_status = "updated";

			} else {

				$package_stmt = $conn->prepare(
					"INSERT INTO packages (name, category, price, badge, quote, features)
					 VALUES (?, ?, ?, ?, ?, ?)"
				);

				$package_stmt->execute([
					$package_name,
					$package_category,
					$package_price,
					$package_badge,
					$package_quote,
					$package_features
				]);

				$package_status = "created";
			}

			$package_stmt = null;
			$_SESSION["package_status"] = $package_status;
			header("Location: admin.php#goi-gia");
			exit;
		}
	}

	if ($action === "delete_package") {

		$package_id = (int) ($_POST["package_id"] ?? 0);

		$package_stmt = $conn->prepare("DELETE FROM packages WHERE id = ?");
		$package_stmt->execute([$package_id]);
		$package_stmt = null;

		$_SESSION["package_status"] = "deleted";
		header("Location: admin.php#goi-gia");
		exit;
	}

	if ($action === "toggle_package") {

		$package_id = (int) ($_POST["package_id"] ?? 0);

		$package_stmt = $conn->prepare(
			"UPDATE packages SET active = NOT active WHERE id = ?"
		);
		$package_stmt->execute([$package_id]);
		$package_stmt = null;

		$_SESSION["package_status"] = "toggled";
		header("Location: admin.php#goi-gia");
		exit;
	}

	if ($action === "delete_contact") {

		$contact_id = (int) ($_POST["contact_id"] ?? 0);

		$contact_stmt = $conn->prepare("DELETE FROM contacts WHERE id = ?");
		$contact_stmt->execute([$contact_id]);
		$contact_stmt = null;

		header("Location: admin.php#lien-he");
		exit;
	}

}

$edit_album = null;
$edit_package = null;


if (isset($_GET["edit_album"])) {

	$edit_album_id = (int) $_GET["edit_album"];

	$edit_stmt = $conn->prepare(
		"SELECT id, title, category, image_url, description
		 FROM albums
		 WHERE id = ?"
	);

	$edit_stmt->execute([$edit_album_id]);

	$edit_album = $edit_stmt->fetch();

	$edit_stmt = null;
}

if (isset($_GET["edit_package"])) {

	$edit_package_id = (int) $_GET["edit_package"];

	$edit_package_stmt = $conn->prepare(
		"SELECT id, name, category, price, badge, quote, features
		 FROM packages
		 WHERE id = ?"
	);

	$edit_package_stmt->execute([$edit_package_id]);
	$edit_package = $edit_package_stmt->fetch();
	$edit_package_stmt = null;
}

$albums = $conn->query(
	"SELECT id, title, category, image_url, description, is_visible
	 FROM albums
	 ORDER BY id DESC"
);

$album_count_result = $conn->query(
	"SELECT COUNT(*) AS total
	 FROM albums
	 WHERE is_visible = 1"
);

$album_count = (int) $album_count_result->fetch()["total"];

function get_table_count($conn, $table_name)
{
	$table_result = $conn->query("SHOW TABLES LIKE " . $conn->quote($table_name));

	if ($table_result->rowCount() === 0) {

		return 0;
	}

	$count_result = $conn->query("SELECT COUNT(*) AS total FROM `$table_name`");

	return (int) $count_result->fetch()["total"];
}




$package_count = get_table_count($conn, "packages");

$packages = $conn->query(
	"SELECT id, name, category, price, badge, features, active
	 FROM packages
	 ORDER BY id DESC"
);

$package_category_names = [
	"cuoi" => "Ảnh cưới",
	"giadinh" => "Ảnh gia đình",
	"nghethuat" => "Ảnh nghệ thuật",
	"makeup" => "Makeup"
];

$contact_count = get_table_count($conn, "contacts");

$contacts = $conn->query(
	"SELECT id, name, phone, info_advise, created_at
	 FROM contacts
	 ORDER BY created_at DESC, id DESC"
);

?>




<!DOCTYPE html>
<html lang="vi">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Quản trị website</title>
	<link rel="stylesheet" href="css/admin.css">
	<script>
		window.cloudinaryConfig = {
			cloudName: "<?= htmlspecialchars($cloudinary_cloud_name, ENT_QUOTES, 'UTF-8') ?>",
			uploadPreset: "<?= htmlspecialchars($cloudinary_upload_preset, ENT_QUOTES, 'UTF-8') ?>"
		};
	</script>
</head>

<body>
	<div class="layout">
		<aside>
			<div class="brand">
				Hoàng Tuấn
				<span>STUDIO</span>
			</div>

			<nav>
				<a href="#tong-quan" class="active">Tổng quan</a>
				<a href="#albums">Quản lý albums</a>
				<a href="#goi-gia">Dịch vụ - gói giá</a>
				<a href="#lien-he">Tin nhắn liên hệ</a>
			</nav>

			<div class="sidebar-actions">
				<a class="logout" href="logout.php">Đăng xuất⇥</a>
			</div>
		</aside>

		<main>
			<header id="tong-quan">
				<div>
					<label>TRANG QUẢN TRỊ</label>
					<a class="web" href="home.php">Xem website»</a>
					<h1>Xin chào, Admin</h1>
					<p>Quản lý nội dung Hoàng Tuấn Studio</p>
				</div>
			</header>

			<section class="welcome">
				<label>OVERVIEW</label>
				<h2>Mọi nội dung quan trọng ở ngay đây.</h2>
				<p>Thêm, cập nhật hoặc xóa dữ liệu trên website của bạn.</p>
			</section>

			<section class="stats">
				<a href="#albums">
					<b><?= $album_count ?></b>
					<span>Albums đang hiện</span>
				</a>

				<a href="#goi-gia">
					<b><?= $package_count ?></b>
					<span>Gói dịch vụ</span>
				</a>

				<a href="#lien-he">
					<b><?= $contact_count ?></b>
					<span>Tin nhắn khách gửi</span>
				</a>
			</section>

			<section class="panel" id="albums">
				<label>ALBUMS</label>
				<h2>Quản lý albums</h2>

				<?php if ($album_notice !== ""): ?>
					<p class="notice success">
						<?= htmlspecialchars($album_notice) ?>
					</p>
				<?php endif; ?>

				<?php if ($album_error !== ""): ?>
					<p class="notice error">
						<?= htmlspecialchars($album_error) ?>
					</p>
				<?php endif; ?>

				<form method="POST">
					<input type="hidden" name="action" value="save_album">
					<input type="hidden" name="album_id" value="<?= $edit_album["id"] ?? "" ?>">
					<input type="text" name="album-title" placeholder="Tên album" value="<?= htmlspecialchars($edit_album["title"] ?? "") ?>">
					<select name="album-category">
						<option value="">Chọn danh mục</option>
						<option value="cuoi" <?= ($edit_album["category"] ?? "") === "cuoi" ? "selected" : "" ?>>Ảnh cưới</option>
						<option value="giadinh" <?= ($edit_album["category"] ?? "") === "giadinh" ? "selected" : "" ?>>Ảnh gia đình</option>
						<option value="nghethuat" <?= ($edit_album["category"] ?? "") === "nghethuat" ? "selected" : "" ?>>Ảnh nghệ thuật</option>
						<option value="makeup" <?= ($edit_album["category"] ?? "") === "makeup" ? "selected" : "" ?>>Makeup</option>
					</select>
					<input type="hidden" name="image_url" id="image-url">
					<input type="hidden" name="image_urls" id="image-urls">
					<input type="file" id="album-image-file" accept="image/*" multiple>
					<small class="file-help">
						Giữ Ctrl hoặc Shift để chọn nhiều ảnh.
					</small>
					<img id="image-preview" class="image-preview" src="" alt="Ảnh album đã chọn" hidden>
					<p id="upload-status" class="upload-status"></p>
					<textarea name="album-description" placeholder="Mô tả album"><?= htmlspecialchars($edit_album["description"] ?? "") ?></textarea>
					<button type="submit">
						<?= $edit_album ? "Cập nhật album" : "Thêm album" ?>
					</button>
					<button class="action-button cancel-button" type="button" onclick="window.location.href='admin.php#albums'">Hủy Sửa</button>
				</form>

				<div class="table-wrap">
					<table>
						<thead>
							<tr>
								<th>Ảnh</th>
								<th>Tên album</th>
								<th>Danh mục</th>
								<th>Thao tác</th>
							</tr>
						</thead>

						<tbody>
							<?php while ($album = $albums->fetch()): ?>
								<tr>
									<td>
										<img
											class="thumb"
											src="<?= htmlspecialchars($album["image_url"]) ?>"
											alt="<?= htmlspecialchars($album["title"]) ?>"
										>
									</td>
									<td><?= htmlspecialchars($album["title"]) ?></td>
									<td><?= htmlspecialchars($album["category"]) ?></td>
									<td>
										<div class="album-actions">
											<a
												class="action-button edit-button"
												href="admin.php?edit_album=<?= $album["id"] ?>#albums"
											>
												Sửa
											</a>

											<form
												class="inline"
												method="POST"
												onsubmit="return confirm('Bạn có chắc muốn xóa album này không?');"
											>
												<input type="hidden" name="action" value="delete_album">
												<input type="hidden" name="album_id" value="<?= $album["id"] ?>">
												<button class="action-button delete-button" type="submit">Xóa</button>
											</form>

											<form class="inline" method="POST">
												<input type="hidden" name="action" value="toggle_album">
												<input type="hidden" name="album_id" value="<?= $album["id"] ?>">
												<button class="action-button toggle-button" type="submit">
													<?= $album["is_visible"] ? "Ẩn" : "Hiện" ?>
												</button>
											</form>
										</div>
									</td>
								</tr>
							<?php endwhile; ?>
						</tbody>
					</table>
				</div>
			</section>

			<section class="panel" id="goi-gia">
				<label>DỊCH VỤ - GÓI GIÁ</label>
				<h2>Quản lý gói dịch vụ</h2>

				<?php if ($package_notice !== ""): ?>
					<p class="notice success">
						<?= htmlspecialchars($package_notice) ?>
					</p>
				<?php endif; ?>

				<?php if ($package_error !== ""): ?>
					<p class="notice error">
						<?= htmlspecialchars($package_error) ?>
					</p>
				<?php endif; ?>

				<form method="POST">
					<input type="hidden" name="action" value="save_package">
					<input type="hidden" name="package_id" value="<?= $edit_package["id"] ?? "" ?>">
					<input type="text" name="package-name" placeholder="Tên gói dịch vụ" value="<?= htmlspecialchars($edit_package["name"] ?? "") ?>" required>

					<select name="package-category" id="package-category" required>
						<option value="">Chọn danh mục</option>
						<option value="cuoi" <?= ($edit_package["category"] ?? "") === "cuoi" ? "selected" : "" ?>>Ảnh cưới</option>
						<option value="giadinh" <?= ($edit_package["category"] ?? "") === "giadinh" ? "selected" : "" ?>>Ảnh gia đình</option>
						<option value="nghethuat" <?= ($edit_package["category"] ?? "") === "nghethuat" ? "selected" : "" ?>>Ảnh nghệ thuật</option>
						<option value="makeup" <?= ($edit_package["category"] ?? "") === "makeup" ? "selected" : "" ?>>Makeup</option>
					</select>
					<input type="number" name="price" placeholder="Giá tiền" value="<?= htmlspecialchars($edit_package["price"] ?? "") ?>" min="0" step="1000" required>
					<input type="text" name="badge" placeholder="Nhãn gói (ví dụ: PHỔ BIẾN NHẤT)" value="<?= htmlspecialchars($edit_package["badge"] ?? "") ?>">
					<input type="text" name="quote" placeholder="Câu giới thiệu ngắn" value="<?= htmlspecialchars($edit_package["quote"] ?? "") ?>">

					<textarea name="package-features" rows="5" placeholder="Các quyền lợi của gói, mỗi quyền lợi một dòng"><?= htmlspecialchars($edit_package["features"] ?? "") ?></textarea>
					<button type="submit"><?= $edit_package ? "Cập nhật gói giá" : "Thêm gói giá" ?></button>
					<button class="action-button cancel-button" type="button" onclick="window.location.href='admin.php#goi-gia'">Hủy Sửa</button>
				</form>

				<div class="table-wrap">
					<table>
						<thead>
							<tr>
								<th>Tên gói</th>
								<th>Danh mục</th>
								<th>Giá</th>
								<th>Badge</th>
								<th>Thao tác</th>
							</tr>
						</thead>

						<tbody>
							<?php while ($package = $packages->fetch()): ?>
								<tr>
									<td><?= htmlspecialchars($package["name"]) ?></td>
									<td><?= htmlspecialchars($package_category_names[$package["category"]] ?? $package["category"]) ?></td>
									<td><?= number_format((float) $package["price"], 0, ",", ".") ?> đ</td>
									<td><?= htmlspecialchars($package["badge"] ?? "") ?></td>
									<td>
										<a class="action-button edit-button" href="admin.php?edit_package=<?= $package["id"] ?>#goi-gia">Sửa</a>

										<form class="inline" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa gói này không?');">
											<input type="hidden" name="action" value="delete_package">
											<input type="hidden" name="package_id" value="<?= $package["id"] ?>">
											<button class="action-button delete-button" type="submit">Xóa</button>
										</form>

										<form class="inline" method="POST">
											<input type="hidden" name="action" value="toggle_package">
											<input type="hidden" name="package_id" value="<?= $package["id"] ?>">
											<button class="action-button toggle-button" type="submit">
												<?= $package["active"] ? "Ẩn" : "Hiện" ?>
											</button>
										</form>
									</td>
								</tr>
							<?php endwhile; ?>
						</tbody>
					</table>
				</div>
			</section>

			<section class="panel" id="lien-he">
				<label>LIÊN HỆ</label>
				<h2>Tin nhắn khách hàng</h2>

				<div class="table-wrap">
					<table>
						<thead>
							<tr>
								<th>Họ tên</th>
								<th>Số điện thoại</th>
								<th>Nội dung cần tư vấn</th>
								<th>Ngày gửi</th>
								<th>Thao tác</th>
							</tr>
						</thead>

						<tbody>
							<?php while ($contact = $contacts->fetch()): ?>
								<tr>
									<td><?= htmlspecialchars($contact["name"]) ?></td>
									<td><?= htmlspecialchars($contact["phone"]) ?></td>
									<td><?= nl2br(htmlspecialchars($contact["info_advise"])) ?></td>
									<td><?= htmlspecialchars(date("d/m/Y H:i", strtotime($contact["created_at"]))) ?></td>
									<td>
										<form class="inline" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa tin nhắn này không?');">
											<input type="hidden" name="action" value="delete_contact">
											<input type="hidden" name="contact_id" value="<?= (int) $contact["id"] ?>">
											<button class="action-button delete-button" type="submit">Xóa</button>
										</form>
									</td>
								</tr>
							<?php endwhile; ?>
						</tbody>
					</table>
				</div>
			</section>
		</main>
	</div>

	<script src="js/admin.js?v=3"></script>
</body>

</html>

