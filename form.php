<?php
session_start();
include 'db/conn.php';

$paketQuery = "SELECT id, nama_paket FROM paket";
$paketResult = $conn->query($paketQuery);

$status = $_SESSION['status'] ?? '';
$message = $_SESSION['message'] ?? '';

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

    <div id="editModal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2>Edit Booking</h2>
            <form id="editForm">
                <input type="hidden" id="edit-id" name="id">
                
                <div class="form-group">
                    <label for="edit-nama">Nama:</label>
                    <input type="text" id="edit-nama" name="nama" required>
                </div>

                <div class="form-group">
                    <label for="edit-email">Email:</label>
                    <input type="email" id="edit-email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="edit-nomor">Nomor Telepon:</label>
                    <input type="text" id="edit-nomor" name="nomor" required>
                </div>

                <div class="form-group">
                    <label for="edit-paket">Paket:</label>
                    <select id="edit-paket" name="paket_id" required>
                        <option value="">-- Pilih Paket --</option>
                        <?php 
                        $paketQuery = "SELECT id, nama_paket FROM paket";
                        $paketResult = $conn->query($paketQuery);
                        while ($row = $paketResult->fetch_assoc()) { ?>
                            <option value="<?= $row['id']; ?>"><?= $row['nama_paket']; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="edit-date">Tanggal:</label>
                    <input type="date" id="edit-date" name="date" required>
                </div>

                <div class="form-group">
                    <label for="edit-time">Waktu:</label>
                    <select id="edit-time" name="time_id" required>
                        <option value="">-- Pilih Waktu --</option>
                    </select>
                </div>

                <div class="button-container">
                    <button type="submit">Simpan Perubahan</button>
                </div>
            </form>
        </div>
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

            <?php if ($message): ?>
                Swal.fire({
                    icon: '<?= $status === "success" ? "success" : "error" ?>',
                    title: '<?= ucfirst($status) ?>',
                    text: '<?= $message ?>',
                    confirmButtonColor: '#3085d6'
                });
            <?php endif; ?>
        });

        $(document).ready(function() {
        $(document).on('click', '.btn-edit', function() {
            const id = $(this).data('id');
            
            $.get('db/update.php?id=' + id, function(response) {
                if (response.error) {
                    Swal.fire('Error', response.error, 'error');
                    return;
                }
                
                $('#edit-id').val(response.id);
                $('#edit-nama').val(response.nama);
                $('#edit-email').val(response.email);
                $('#edit-nomor').val(response.nomor_telp);
                $('#edit-paket').val(response.paket_id);
                $('#edit-date').val(response.date);
                
                updateAvailableTimes(response.paket_id, response.date, response.time_id);
                
                $('#editModal').show();
            }).fail(function() {
                Swal.fire('Error', 'Gagal memuat data', 'error');
            });
        });
        
        $('.close-modal').click(function() {
            $('#editModal').hide();
        });
        
        $('#edit-paket, #edit-date').change(function() {
            const paketId = $('#edit-paket').val();
            const date = $('#edit-date').val();
            
            if (paketId && date) {
                updateAvailableTimes(paketId, date);
            } else {
                $('#edit-time').html('<option value="">Pilih Paket dan Tanggal terlebih dahulu</option>');
            }
        });
        
        $('#editForm').submit(function(e) {
            e.preventDefault();
            
            const formData = {
                id: $('#edit-id').val(),
                nama: $('#edit-nama').val(),
                email: $('#edit-email').val(),
                nomor: $('#edit-nomor').val(),
                paket_id: $('#edit-paket').val(),
                date: $('#edit-date').val(),
                time_id: $('#edit-time').val()
            };
            
            $.ajax({
                url: 'db/update.php',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(formData),
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Sukses', 'Data berhasil diupdate', 'success')
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Error', response.error || 'Gagal mengupdate data', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Terjadi kesalahan', 'error');
                }
            });
        });
    });

        function updateAvailableTimes(paketId, date, selectedTimeId = null) {
            $.get(`db/get_time.php?paket_id=${paketId}&date=${date}`, function(times) {
                const $timeSelect = $('#edit-time');
                $timeSelect.empty();
                
                if (times.length > 0) {
                    $timeSelect.append('<option value="">Pilih Waktu</option>');
                    times.forEach(time => {
                        const selected = time.id == selectedTimeId ? 'selected' : '';
                        $timeSelect.append(`<option value="${time.id}" ${selected}>${time.waktu}</option>`);
                    });
                } else {
                    $timeSelect.append('<option value="">Tidak ada waktu tersedia</option>');
                }
            });
        }
    </script>
</body>
</html>
