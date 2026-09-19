<?php
require "connect.php";
require "authentication.php"; // chỉ admin đã đăng nhập mới được upload ảnh

$CLOUD_NAME = "Vanquy";
$UPLOAD_PRESET = "Vanquy";

if (!isset($_FILES["image"]) || $_FILES["image"]["error"] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Không nhận được file ảnh hợp lệ."]);
    exit;
}

$allowedTypes = ["image/jpeg", "image/png", "image/webp"];
$fileType = mime_content_type($_FILES["image"]["tmp_name"]);
if (!in_array($fileType, $allowedTypes)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Chỉ chấp nhận ảnh JPG, PNG hoặc WEBP."]);
    exit;
}

if ($_FILES["image"]["size"] > 8 * 1024 * 1024) { // giới hạn 8MB
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Ảnh không được vượt quá 8MB."]);
    exit;
}

$uploadUrl = "https://api.cloudinary.com/v1_1/{$CLOUD_NAME}/image/upload";

    $curlFile = new CURLFile(
    $_FILES["image"]["tmp_name"],
    $fileType,
    $_FILES["image"]["name"]
);

$postFields = [
    "file" => $curlFile,
    "upload_preset" => $UPLOAD_PRESET
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $uploadUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($curlError) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Lỗi kết nối Cloudinary: " . $curlError]);
    exit;
}

$result = json_decode($response, true);

if ($httpCode !== 200 || !isset($result["secure_url"])) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => $result["error"]["message"] ?? "Upload thất bại."]);
    exit;
}

echo json_encode([
    "success" => true,
    "url" => $result["secure_url"]
]);