<?php
session_start();
include 'db/conn.php';

// Ambil daftar paket dari database
$paketQuery = "SELECT id, nama_paket FROM paket";
$paketResult = $conn->query($paketQuery);

// Ambil pesan dari session
$status = $_SESSION['status'] ?? '';
$message = $_SESSION['message'] ?? '';

// Hapus session setelah ditampilkan
unset($_SESSION['status'], $_SESSION['message']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Booking Studio</title>
    <link rel="stylesheet" href="styles/form_style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <h2>Form Booking Studio</h2>

        <form id="bookingForm" method="POST" action="./db/create.php">
            <div class="form-group">
                <label for="nama">Nama:</label>
                <input type="text" id="nama" name="nama">
                <small class="error" id="error-nama"></small>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email">
                <small class="error" id="error-email"></small>
            </div>

            <div class="form-group">
                <label for="nomor">Nomor Telepon:</label>
                <input type="text" id="nomor" name="nomor">
                <small class="error" id="error-nomor"></small>
            </div>

            <div class="form-group">
                <label for="paket">Pilih Paket:</label>
                <select id="paket" name="paket_id">
                    <option value="">-- Pilih Paket --</option>
                    <?php while ($row = $paketResult->fetch_assoc()) { ?>
                        <option value="<?= $row['id']; ?>"><?= $row['nama_paket']; ?></option>
                    <?php } ?>
                </select>
                <small class="error" id="error-paket"></small>
            </div>

            <div class="form-group">
                <label for="date">Pilih Tanggal:</label>
                <input type="date" id="date" name="date">
                <small class="error" id="error-date"></small>
            </div>

            <div class="form-group">
                <label for="time">Pilih Waktu:</label>
                <select id="time" name="time_id">
                    <option value="">-- Pilih Paket Dulu --</option>
                </select>
                <small class="error" id="error-time"></small>
            </div>

            <div class="button-container">
                <button type="submit">Booking</button>
            </div>
        </form>

        <h2>Daftar Booking</h2>
        <?php include 'db/show.php'; ?>

    </div>

    <script>
        $(document).ready(function() {
            $('#paket, #date').change(function() {
                var paketId = $('#paket').val();
                var selectedDate = $('#date').val();
                var $timeSelect = $('#time');

                $timeSelect.html('<option value="">Memuat...</option>');

                if (paketId && selectedDate) {
                    $.ajax({
                        url: 'db/get_time.php',
                        type: 'GET',
                        data: { paket_id: paketId, date: selectedDate },
                        dataType: 'json',
                        success: function(response) {
                            $timeSelect.html('<option value="">-- Pilih Waktu --</option>');
                            if (response.length > 0) {
                                $.each(response, function(index, waktu) {
                                    $timeSelect.append('<option value="'+ waktu.id +'">'+ waktu.waktu +'</option>');
                                });
                            } else {
                                $timeSelect.html('<option value="">Tidak ada waktu tersedia</option>');
                            }
                        },
                        error: function() {
                            $timeSelect.html('<option value="">Gagal memuat waktu</option>');
                        }
                    });
                } else {
                    $timeSelect.html('<option value="">-- Pilih Paket & Tanggal Dulu --</option>');
                }
            });

            // Menampilkan SweetAlert jika ada pesan dari session
            <?php if ($message): ?>
                Swal.fire({
                    icon: '<?= $status === "success" ? "success" : "error" ?>',
                    title: '<?= ucfirst($status) ?>',
                    text: '<?= $message ?>',
                    confirmButtonColor: '#3085d6'
                });
            <?php endif; ?>
        });
    </script>
</body>
</html>
