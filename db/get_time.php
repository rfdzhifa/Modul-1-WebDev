<?php
include 'conn.php'; // Sesuaikan path jika perlu

header('Content-Type: application/json');

if (isset($_GET['paket_id']) && isset($_GET['date'])) {
    $paket_id = $_GET['paket_id'];
    $date = $_GET['date'];

    $query = "
        SELECT t.id, t.waktu 
        FROM time t
        WHERE t.paket_id = ? 
        AND t.id NOT IN (
            SELECT time_id FROM booking WHERE paket_id = ? AND date = ?
        )
        ORDER BY t.waktu ASC
    ";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo json_encode(["error" => "Query error: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("iis", $paket_id, $paket_id, $date);
    $stmt->execute();
    $result = $stmt->get_result();

    $waktu = [];
    while ($row = $result->fetch_assoc()) {
        $waktu[] = $row;
    }

    echo json_encode($waktu);
    exit;
} else {
    echo json_encode(["error" => "Parameter tidak lengkap"]);
    exit;
}
?>
