<?php
session_start();
include 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST["nama"];
    $email = $_POST["email"];
    $nomor = $_POST["nomor"];
    $paket = $_POST["paket_id"];
    $date = $_POST["date"];
    $time = $_POST["time_id"];

    $errors = [];

    if (empty($nama)) $errors['nama'] = "Nama wajib diisi!";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = "Email tidak valid!";
    if (empty($nomor) || !ctype_digit($nomor)) $errors['nomor'] = "Nomor harus angka!";
    if (empty($paket)) $errors['paket_id'] = "Harap pilih paket!";
    if (empty($date)) $errors['date'] = "Tanggal wajib diisi!";
    if (empty($time)) $errors['time_id'] = "Waktu wajib diisi!";

    if (!empty($errors)) {
        $_SESSION['status'] = "error";
        $_SESSION['message'] = "Harap isi semua field dengan benar!";
        header("Location: ../form.php");
        exit();
    }

    $stmtHarga = $conn->prepare("SELECT harga FROM paket WHERE id = ?");
    $stmtHarga->bind_param("s", $paket);
    $stmtHarga->execute();
    $resultHarga = $stmtHarga->get_result();
    $harga = ($resultHarga->num_rows > 0) ? $resultHarga->fetch_assoc()['harga'] : 0;

    if ($harga == 0) {
        $_SESSION['status'] = "error";
        $_SESSION['message'] = "Paket tidak ditemukan!";
        header("Location: ../form.php");
        exit();
    }

    $stmtCheck = $conn->prepare("SELECT * FROM booking WHERE paket_id = ? AND date = ? AND time_id = ?");
    $stmtCheck->bind_param("sss", $paket, $date, $time);
    $stmtCheck->execute();
    if ($stmtCheck->get_result()->num_rows > 0) {
        $_SESSION['status'] = "error";
        $_SESSION['message'] = "Waktu sudah dipesan!";
        header("Location: ../form.php");
        exit();
    }

    $stmtInsert = $conn->prepare("INSERT INTO booking (nama, email, nomor_telp, paket_id, date, time_id, harga) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmtInsert->bind_param("ssssssi", $nama, $email, $nomor, $paket, $date, $time, $harga);

    $_SESSION['status'] = $stmtInsert->execute() ? "success" : "error";
    $_SESSION['message'] = $_SESSION['status'] === "success" ? "Booking berhasil!" : "Terjadi kesalahan!";

    header("Location: ../form.php");
    exit();
}
