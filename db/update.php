<?php
session_start();
include 'conn.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Handle GET request (ambil data booking)
    if (!isset($_GET['id'])) {
        echo json_encode(['error' => 'ID tidak valid']);
        exit;
    }

    $id = $_GET['id'];
    $query = "SELECT b.*, t.waktu FROM booking b JOIN time t ON b.time_id = t.id WHERE b.id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['error' => 'Data tidak ditemukan']);
        exit;
    }

    echo json_encode($result->fetch_assoc());
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $id = $data['id'];
    $nama = $data['nama'];
    $email = $data['email'];
    $nomor = $data['nomor'];
    $paket_id = $data['paket_id'];
    $date = $data['date'];
    $time_id = $data['time_id'];

    $errors = [];
    if (empty($nama)) $errors[] = 'Nama wajib diisi';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email tidak valid';
    if (empty($nomor)) $errors[] = 'Nomor wajib diisi';
    if (empty($paket_id)) $errors[] = 'Paket wajib dipilih';
    if (empty($date)) $errors[] = 'Tanggal wajib diisi';
    if (empty($time_id)) $errors[] = 'Waktu wajib dipilih';

    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode(['errors' => $errors]);
        exit;
    }

    $query = "UPDATE booking SET nama=?, email=?, nomor_telp=?, paket_id=?, date=?, time_id=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssi", $nama, $email, $nomor, $paket_id, $date, $time_id, $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Gagal menyimpan data']);
    }
    exit;
}