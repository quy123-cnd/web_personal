<?php

require_once "connect.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: contact.php");
    exit;
}

$name = trim($_POST["name"] ?? "");
$phone = trim($_POST["phone"] ?? "");
$info_advise = trim($_POST["info-advise"] ?? "");

if ($name === "" || $phone === "" || $info_advise === "") {
    header("Location: contact.php?error=missing");
    exit;
}

if (!preg_match("/^(03|05|07|08|09)[0-9]{8}$/", $phone)) {
    header("Location: contact.php?error=phone");
    exit;
}

$stmt = $conn->prepare(
    "INSERT INTO contacts (name, phone, info_advise)
     VALUES (?, ?, ?)"
);
$stmt->execute([$name, $phone, $info_advise]);

header("Location: contact.php?sent=1");
exit;
